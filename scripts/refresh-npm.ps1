param (
	[switch]$packagesOnly
)

Write-Host "Starting NPM refresh script"

# Function to run composer commands in a directory
function Run-NPM {
	param (
		[string]$directory,
		[switch]$packagesOnly
	)
	Write-Host "Running NPM commands in $directory"
	Push-Location $directory

	npm install
	npm run build

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

Write-Host "All done!"
