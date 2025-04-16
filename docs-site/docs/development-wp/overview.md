---
position: 0
---

# Overview

:::info
This section of the docs covers developing the Comet Components suite of WordPress plugins (Comet Plugin, Comet Calendar, Comet Table Block, etc.) themselves.

For guidance on implementing Comet Components in your own plugins and themes, see the [Wordpress usage](../usage/wordpress.md) section.
:::

[[toc]]

## Prerequisites
- [Composer](https://getcomposer.org/) installed on your machine
- Local WordPress installation to use for testing
- [Local dev setup](../development-core/setup.md) for the Comet Components project as a whole
- The [Comet Components monorepo](https://github.com/doubleedesign/comet-components) cloned to your local machine and open in your IDE
- Sufficient permissions to create symlinks.

:::warning
Make sure any [PhpStorm file watchers](../local-dev-deep-dives/tooling-guides/phpstorm.md#file-watchers) you have for your WordPress site are scoped to your own plugin and theme. If you leave them scoped to the entire project, they'll try to compile the various Comet packages' assets, which you don't need it to do _and_ won't work because of file path differences.
:::

## Setup

To use your local copy of Comet Components packages in your dev site instead of the published versions that Composer will install by default, use symbolic links (symlinks). This is a two-step process:

1. In the `comet-components` project directory, refresh all dependencies with:

   ```bash
   npm run refresh:all:dev
   ```
   The `:dev` version of the refresh script uses `composer.local.json` where available, which should be configured to symlink local package usages (e.g., the `comet-plugin` package's installation of `comet-components-core`).

2. In your WordPress site, if you have already installed the theme and plugins using the [suggested Composer configuration](../installation/wordpress.md):
	- delete those directories
	- add the below script to your project root as `symlinks.ps1` or (or equivalent Bash script), and update any paths as necessary

:::details PowerShell script to create symlinks from the dev packages to a WordPress installation
```powershell
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

$PROJECT_DIR = Split-Path -Parent $MyInvocation.MyCommand.Path
$CURRENT_USER = ([System.Security.Principal.WindowsIdentity]::GetCurrent().Name).Split('\')[1]
$USER_DIR = "C:\Users\$CURRENT_USER"

# Define the real directories/files and their corresponding symlink directories/files
# This assumes we're in a site in C:/USERNAME/LocalSites, and other repos are in C:/USERNAME/PHPStormProjects
# Remove the paths to any plugins you're not using
$symlinks = @(
	@{ destination = "$PROJECT_DIR\app\public\wp-content\plugins\comet-plugin"; source = "$USER_DIR\PHPStormProjects\comet-components\packages\comet-plugin" }
	@{ destination = "$PROJECT_DIR\app\public\wp-content\plugins\comet-calendar"; source = "$USER_DIR\PHPStormProjects\comet-components\packages\comet-calendar" },
	@{ destination = "$PROJECT_DIR\app\public\wp-content\themes\comet-canvas"; source = "$USER_DIR\PHPStormProjects\comet-components\packages\comet-canvas" },
	@{ destination = "$PROJECT_DIR\app\public\wp-content\plugins\comet-table-block"; source = "$USER_DIR\PHPStormProjects\comet-table-block" }
)

# Function to create directories if they don't exist
function Ensure-DirectoryExists {
	param (
		[string]$directoryPath
	)
	if (-not (Test-Path $directoryPath)) {
		New-Item -ItemType Directory -Path $directoryPath -Force | Out-Null
		Write-Host "Created directory: $directoryPath"
	}
}

# Function to create symbolic links for both files and directories
function Create-Symlink {
	param (
		[string]$realPath,
		[string]$symlinkPath
	)

	# Ensure the real path exists
	Ensure-DirectoryExists -directoryPath $realPath
	$resolvedRealPath = Resolve-Path -Path $realPath

	# Ensure the parent directory of the symlink exists
	$symlinkDir = Split-Path -Parent $symlinkPath
	Ensure-DirectoryExists -directoryPath $symlinkDir

	# Create the symlink
	if (!(Test-Path $symlinkPath)) {
		Write-Host "Creating directory symlink: $symlinkPath -> $resolvedRealPath"
		New-Item -ItemType SymbolicLink -Path $symlinkPath -Target $resolvedRealPath | Out-Null
	}
	else {
		$item = Get-Item $symlinkPath -Force
		if ($item.LinkType -eq "SymbolicLink") {
			Write-Host "Symlink already exists: $symlinkPath"
		}
		else {
			Write-Host "Warning: Path exists but is not a symlink: $symlinkPath. Deleting folder and symlinking to $resolvedRealPath"
			Remove-Item -Path $symlinkPath -Recurse
			New-Item -ItemType SymbolicLink -Path $symlinkPath -Target $resolvedRealPath | Out-Null
		}
	}

	# Confirm symlink creation
	if (Test-Path $symlinkPath) {
		$item = Get-Item $symlinkPath -Force
		if ($item.LinkType -eq "SymbolicLink") {
			Write-Host "Verified symlink: $symlinkPath -> $( $item.Target )"
		}
	}
}

# Function to safely delete a symlink
function Remove-Symlink {
	param (
		[string]$symlinkPath
	)

	if (Test-Path $symlinkPath) {
		$item = Get-Item $symlinkPath -Force
		if ($item.LinkType -eq "SymbolicLink") {
			Remove-Item -Path $symlinkPath -Recurse
			Write-Host "Deleted symlink: $symlinkPath"
		}
		else {
			Write-Host "Warning: Path exists but is not a symlink, skipping deletion: $symlinkPath"
		}
	}
	else {
		Write-Host "Path does not exist: $symlinkPath"
	}
}

# Iterate over each pair of real and symlink directories/files
foreach ($link in $symlinks) {
	# Safely remove existing symlink if it exists
	Remove-Symlink -symlinkPath $link.destination
	# Create/re-create the symlink
	Create-Symlink -realPath $link.source -symlinkPath $link.destination
}
```
:::

3. Run it with:

   ```powershell:no-line-numbers
   .\symlinks.ps1
   ```

