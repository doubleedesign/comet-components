param (
	[switch]$autoload,
	[switch]$local
)

Write-Host "Starting Composer refresh script" -ForegroundColor Green

# Function to run composer commands in a directory
function Run-Composer {
	param (
		[string]$directory,
		[switch]$autoloadOnly,
		[switch]$local
	)
	Write-Host "Running composer commands in $directory"
	Push-Location $directory

	# Remove existing core package when it is a dependency of another package
	$corePackagePath = Join-Path $directory "vendor\doubleedesign\comet-components-core"
	if (Test-Path $corePackagePath) {
		Remove-Item -Recurse -Force $corePackagePath
	}

	if (-not $autoloadOnly) {
		# Use composer.local.json if available
		$fileExists = Test-Path "$directory\composer.local.json"
		if ($local -and $fileExists) {
			Write-Host "Using local dev Composer configuration for $directory" -ForegroundColor Cyan
			$env:COMPOSER = "composer.local.json"; composer update --prefer-source
		}
		else {
			Write-Host "Using default Composer configuration for $directory" -ForegroundColor Cyan
			composer update --prefer-source
		}
	}
	composer dump-autoload -o

	Pop-Location
}

# Store the root directory
$ROOT_DIR = Get-Location

# Clear cache
composer clear-cache

# Run composer commands in root
Run-Composer $ROOT_DIR -autoloadOnly:$autoload

# Run composer commands in each package
Get-ChildItem -Directory "packages" | ForEach-Object {
	Run-Composer $_.FullName -autoloadOnly:$autoload -local:$local
}

Write-Host "All done!" - ForegroundColor Green
