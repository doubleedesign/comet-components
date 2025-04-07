# Check if running as administrator
function Test-Admin {
	$currentUser = New-Object Security.Principal.WindowsPrincipal([Security.Principal.WindowsIdentity]::GetCurrent())
	return $currentUser.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
}

# If not running as admin, restart the script with elevation
if (-not (Test-Admin)) {
	Write-Host "Requesting administrative privileges..." -ForegroundColor Yellow
	Start-Process powershell.exe -ArgumentList "-NoExit -ExecutionPolicy Bypass -File `"$PSCommandPath`"" -Verb RunAs
	exit
}

# Get the script's directory and navigate up to project root
$SCRIPT_DIR = Split-Path -Parent $MyInvocation.MyCommand.Path
$ROOT_DIR = (Get-Item $SCRIPT_DIR).Parent.FullName
$SOURCE_DIR = "$ROOT_DIR\packages\core\src\components"
$DIST_DIR = "$ROOT_DIR\packages\core\dist"

# Debug output to verify paths
Write-Host "Root directory: $ROOT_DIR"
Write-Host "Source directory: $SOURCE_DIR"

# Ensure the assets destination directory exists and create it if not
$ASSETS_DEST_DIR = "$ROOT_DIR\test\browser\assets"
if (-not (Test-Path $ASSETS_DEST_DIR)) {
	New-Item -ItemType Directory -Path $ASSETS_DEST_DIR -Force
}

# Ensure the stories destination directory exists and create it if not
$STORIES_DEST_DIR = "$ROOT_DIR\test\browser\stories"
if (-not (Test-Path $STORIES_DEST_DIR)) {
	New-Item -ItemType Directory -Path $STORIES_DEST_DIR -Force
}

# Ensure the browser components directory exists and create it if not
$BROWSER_COMPONENTS_DIR = "$ROOT_DIR\test\browser\components"
if (-not (Test-Path $BROWSER_COMPONENTS_DIR)) {
	New-Item -ItemType Directory -Path $BROWSER_COMPONENTS_DIR -Force
}

# Ensure the browser pages directory exists and create it if not
$BROWSER_PAGES_DIR = "$ROOT_DIR\test\browser\pages"
if (-not (Test-Path $BROWSER_PAGES_DIR)) {
	New-Item -ItemType Directory -Path $BROWSER_PAGES_DIR -Force
}

# Verify source directory exists
if (-not (Test-Path $SOURCE_DIR)) {
	Write-Error "Source directory not found: $SOURCE_DIR"
	exit 1
}


# Utility function to create a symlink
function Create-Symlink {
	param (
		[string]$Source,
		[string]$Destination
	)

	if (Test-Path $Destination) {
		Write-Host "Removing existing symlink: $Destination"
		Remove-Item $Destination -Force
	}

	Write-Host "Creating symlink: $Source -> $Destination"
	New-Item -ItemType SymbolicLink -Path $Destination -Value $Source -Force
}

# Link the dist files to the assets directory
Get-ChildItem -Path $DIST_DIR -Recurse | ForEach-Object {
	# Ignore directories
	if ($_.PSIsContainer) {
		return
	}

	# Create the destination path in the assets directory
	$DestPath = Join-Path $ASSETS_DEST_DIR $_.Name
	Create-Symlink -Source $_.FullName -Destination $DestPath
}

# Get all CSS files and symlink to the assets folder, ignoring the parent directory structure
Get-ChildItem -Path $SOURCE_DIR -Filter "*.css" -Recurse | ForEach-Object {
	# Just use the filename for the destination
	$DestPath = Join-Path $ASSETS_DEST_DIR $_.Name
	Create-Symlink -Source $_.FullName -Destination $DestPath
}

# Get files from __tests__ in each component directory and symlink them to the relevant browser test directory
Get-ChildItem -Path $SOURCE_DIR -Recurse | ForEach-Object {
	# Is it a directory?
	if (-not $_.PSIsContainer) {
		return
	}

	# Skip if the test directory doesn't exist
	$TEST_DIR = "$SOURCE_DIR\$_\__tests__\"
	if (-not (Test-Path $TEST_DIR)) {
		return
	}

	Get-ChildItem -Path $TEST_DIR | ForEach-Object {
		# Ignore unit test files (format PascalCaseTest.php)
		if ($_.Name -match "([A-Z][a-z]+)Test\.php") {
			return
		}

		# Is it a story file? (Format short-name.stories.json)
		if ($_.Name -match "([a-zA-Z]+)\.stories\.json") {
			$DestFileName = $matches[1] + ".stories.json"
			$DestPath = Join-Path $STORIES_DEST_DIR $DestFileName
			Create-Symlink -Source $_.FullName -Destination $DestPath
		}

		# Is it a component file? (Format short-name.php)
		if ($_.Name -match "([a-zA-Z]+)\.php") {
			$DestFileName = $matches[1] + ".php"
			$DestPath = Join-Path $BROWSER_COMPONENTS_DIR $DestFileName
			Create-Symlink -Source $_.FullName -Destination $DestPath
		}

		# Is it the pages directory? If so, go into it and get any .php files (they can have various names)
		if ($_.Name -eq "pages") {
			Get-ChildItem -Path $TEST_DIR\pages | ForEach-Object {
				$DestFileName = $_.Name
				$DestPath = Join-Path $BROWSER_PAGES_DIR $DestFileName
				Create-Symlink -Source $_.FullName -Destination $DestPath
			}
		}
	}
}
