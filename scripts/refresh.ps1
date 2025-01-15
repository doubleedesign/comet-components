Write-Host "Starting refresh script..."

# Function to run composer commands in a directory
function Run-Composer {
    param (
        [string]$directory
    )
    Write-Host "Running composer commands in $directory"
    Push-Location $directory
    composer update
    composer dump-autoload
    Pop-Location
}

# Store the root directory
$ROOT_DIR = Get-Location

# Run composer commands in root
Run-Composer $ROOT_DIR

# Run composer commands in each package
Get-ChildItem -Directory "packages" | ForEach-Object {
    Run-Composer $_.FullName
}

Write-Host "All done!"
