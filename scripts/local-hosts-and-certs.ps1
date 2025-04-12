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

# Add entry to hosts file to load Storybook at storybook.comet-components.test
$hostsFile = "$env:SystemRoot\System32\drivers\etc\hosts"
$newEntry = "127.0.0.1    storybook.comet-components.test"
$hostsContent = Get-Content -Path $hostsFile -Raw
if ($hostsContent -match [regex]::Escape($newEntry)) {
	Write-Host "The entry already exists in the hosts file." -ForegroundColor Yellow
}
else {
	Add-Content -Path $hostsFile -Value "`n$newEntry" -Force
	Write-Host "Successfully added '$newEntry' to the hosts file." -ForegroundColor Green
}

# Navigate to project root via the directory this script is in
$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectRoot = Split-Path $projectRoot -Parent
Set-Location $projectRoot

# Generate self-signed cert for Storybook
openssl req -x509 -nodes -days 825 -newkey rsa:2048 -keyout ".cert\key.pem" -out ".cert\cert.pem" -config ".cert\openssl.cnf"

# Trust them system-wide
$saved = Get-ChildItem Cert:\LocalMachine\Root | Where-Object { $_.Subject -like "*storybook.comet-components.test*" }
if ($saved) {
	Write-Host "Replacing the existing Storybook certificate" -ForegroundColor Yellow
	$existingCert = Get-ChildItem Cert:\LocalMachine\Root | Where-Object { $_.Subject -like "*storybook.comet-components.test*" }
	$existingCert | Remove-Item -Force
	Import-Certificate -FilePath .cert\cert.pem -CertStoreLocation Cert:\LocalMachine\Root
	$savedNow = Get-ChildItem Cert:\LocalMachine\Root | Where-Object { $_.Subject -like "*storybook.comet-components.test*" }
	if ($savedNow) {
		Write-Host "Storybook certificate saved to the system store" -ForegroundColor Green
	}
	else {
		Write-Host "Failed to save the Storybook certificate to the system store" -ForegroundColor Red
	}
}
else {
	Import-Certificate -FilePath .cert\cert.pem -CertStoreLocation Cert:\LocalMachine\Root
	$savedNow = Get-ChildItem Cert:\LocalMachine\Root | Where-Object { $_.Subject -like "*storybook.comet-components.test*" }
	if ($savedNow) {
		Write-Host "Storybook certificate saved to the system store" -ForegroundColor Green
	}
	else {
		Write-Host "Failed to save the Storybook certificate to the system store" -ForegroundColor Red
	}
}

# Navigate to the certs in the Herd config directory
$herdConfigDir = Join-Path $env:USERPROFILE ".config\herd\config\valet\Certificates"
Set-Location $herdConfigDir
# Ensure the Herd cert is trusted
$saved = Get-ChildItem Cert:\LocalMachine\Root | Where-Object { $_.Subject -like "*comet-components.test*" }
if ($saved) {
	Write-Host "Laravel Herd project certificate is already trusted" -ForegroundColor Green
}
else {
	Import-Certificate -FilePath comet-components.test.crt -CertStoreLocation Cert:\LocalMachine\Root
	$savedNow = Get-ChildItem Cert:\LocalMachine\Root | Where-Object { $_.Subject -like "*comet-components.test*" }
	if ($savedNow) {
		Write-Host "Laravel Herd project certificate saved to the system store" -ForegroundColor Green
	}
	else {
		Write-Host "Failed to save the Laravel Herd project certificate to the system store" -ForegroundColor Red
	}
}
