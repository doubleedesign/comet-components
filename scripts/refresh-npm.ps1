param (
	[switch]$packagesOnly
)

Write-Host "Starting NPM refresh script"

# Function to run NPM commands in a directory
function Run-NPM {
	param (
		[string]$directory,
		[switch]$packagesOnly
	)

	# If there is no package.json file, skip this directory
	if (-not (Test-Path "$directory\package.json")) {
		Write-Host "No package.json file found in $directory. Skipping."
		return
	}

	Write-Host "Running NPM commands in $directory"
	Push-Location $directory

	# In root directory, run with --legacy-peer-deps
	if ($directory -eq $ROOT_DIR) {
		Write-Host "Running NPM install with --legacy-peer-deps"
		npm install --legacy-peer-deps
	} else {
		Write-Host "Running NPM install"
		npm install
	}

	Pop-Location
}

# Store the root directory
$ROOT_DIR = Get-Location

# Run commands in root unless --packagesOnly argument was passed
if (-not $packagesOnly) {
	Run-NPM $ROOT_DIR
}

# Run commands in each package
Get-ChildItem -Directory "packages" | ForEach-Object {
	Run-NPM $_.FullName
}

# Run commands for documentation site
Run-NPM "$ROOT_DIR\docs-site"

Write-Host "All done!"
