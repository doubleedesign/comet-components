Write-Host "Starting refresh script..."

# Function to run composer commands in a directory
function Run-Composer {
	param (
		[string]$directory
	)
	Write-Host "Running composer commands in $directory"
	Push-Location $directory

	# Remove existing core package when it is a dependency of another package
	$corePackagePath = Join-Path $directory "vendor\doubleedesign\comet-components-core"
	if (Test-Path $corePackagePath) {
		Remove-Item -Recurse -Force $corePackagePath
	}

	composer update --prefer-source
	composer dump-autoload -o

	Pop-Location
}

# Store the root directory
$ROOT_DIR = Get-Location

# Clear cache
composer clear-cache

# Run composer commands in root
Run-Composer $ROOT_DIR

# Run composer commands in each package
Get-ChildItem -Directory "packages" | ForEach-Object {
	Run-Composer $_.FullName
}

Write-Host "All done!"
