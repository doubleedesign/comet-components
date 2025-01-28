# Get the script's directory and navigate up to project root
$SCRIPT_DIR = Split-Path -Parent $MyInvocation.MyCommand.Path
$ROOT_DIR = (Get-Item $SCRIPT_DIR).Parent.FullName
$SOURCE_DIR = "$ROOT_DIR\packages\core\src\components"

# Debug output to verify paths
Write-Host "Root directory: $ROOT_DIR"
Write-Host "Source directory: $SOURCE_DIR"

# Ensure the destination directory exists
$DEST_DIR = "$ROOT_DIR\test\browser\assets"
if (-not (Test-Path $DEST_DIR)) {
    New-Item -ItemType Directory -Path $DEST_DIR -Force
}

# Verify source directory exists
if (-not (Test-Path $SOURCE_DIR)) {
    Write-Error "Source directory not found: $SOURCE_DIR"
    exit 1
}

# Get all CSS files, ignoring the parent directory structure
Get-ChildItem -Path $SOURCE_DIR -Filter "*.css" -Recurse | ForEach-Object {
    # Just use the filename for the destination
    $DestPath = Join-Path $DEST_DIR $_.Name

    Write-Host "Symlinking $($_.FullName) to $DestPath"

    # Remove existing symlink if it exists
    if (Test-Path $DestPath) {
        Remove-Item $DestPath -Force
    }

    New-Item -ItemType SymbolicLink -Path $DestPath -Value $_.FullName -Force
}
