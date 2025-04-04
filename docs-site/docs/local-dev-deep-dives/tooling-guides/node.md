---
title: Node on Windows
---

# Node development setup on Windows

[[toc]]

## Installing Node natively in Windows

There are a number of ways you can install Node natively in Windows, including:

1. Using [NVM for Windows](https://github.com/coreybutler/nvm-windows) (preferred for if you need the ability to switch between Node versions)
2. [Laravel Herd](https://herd.laravel.com/windows) also includes Node and NVM[^1].
3. Using the [Chocolatey](https://community.chocolatey.org/) package manager through PowerShell[^2]
4. Downloading and running the installer from [nodejs.org](https://nodejs.org) (quickest and easiest in the short term, but not great for updates or switching between versions easily)

For ease of version management, using NVM for Windows (if you prefer the command line) or Laravel Herd (if
you prefer a GUI and are already using it for PHP and Composer) is recommended.

[^1]:  Herd is a good option if you don't have any pre-existing stuff that might conflict, or are happy to use it for all Node management in Windows.
[^2]: Chocolatey is great for a lot of things, but I wouldn't recommend it for Node unless you don't expect to ever need to switch between Node versions easily.

:::warning
Unlike for [PHP](./php.md) where you can have multiple instances from Chocolatey, Herd, Local, and other web server applications installed concurrently without issue, you can't use Herd to manage Node if you have NVM for Windows installed independently. (Not sure about Chocolatey.)

So if you have NVM but want to use Herd for Node version management now, you'll need to uninstall NVM first and then re-install it through the Herd UI.
:::

In any case, once you've installed Node using your method of choice, all commands will be the same from there other than
upgrading or switching between versions.

Confirm that the `node` alias works in PowerShell (and check your version) like so:

::: tabs#shell
@tab PowerShell
```powershell:no-line-numbers
node -v
```
:::

If this doesn't work, you may need to manually add Node to your [PATH system environment variable](../setup.md#general-notes-and-troubleshooting-tips).

To check where PowerShell is loading Node from if you have multiple instances, you can use:

::: tabs#shell
@tab PowerShell
```powershell:no-line-numbers
Get-Command node
```
:::

:::tip Middle ground: Using Windows' Node in WSL

Like for [Composer](./php.md#optional-use-windows-composer-in-wsl), after following the native installation instructions above you can use Windows' Node installation in WSL by creating aliases to Node and NPM in your Bash configuration file (
`.bashrc` or `.zshrc`) like so:

```bash
# # /home/leesa/.zshrc
alias node='/mnt/c/Program\ Files/nodejs/node.exe'
alias npm='powershell.exe /c npm'
```

Restart the WSL terminal before trying to use these aliases.
:::

## Installing Node natively in WSL

You can also install Node directly in WSL using its own version of [NVM](https://github.com/nvm-sh/nvm?tab=readme-ov-file#installing-and-updating).

PhpStorm's various configuration options that involve Node pick up WSL's Node instance as an option, so ensuring you're using the same instance across all tools is generally straightforward once Node is installed.

::: details Step 1: Install NVM

Instructions for installing NVM on WSL can be found in:

- [Microsoft's WSL Docs > Install NVM, Node.js and NPM](https://learn.microsoft.com/en-us/windows/dev-environment/javascript/nodejs-on-wsl#install-nvm-nodejs-and-npm)
- The [NVM GitHub repo](https://github.com/nvm-sh/nvm?tab=readme-ov-file#installing-and-updating).

:::

::: details Step 2: Install Node

Once you have NVM installed within WSL (_not_ referring to Windows NVM here, if you have it), you can install any version of node using the
`nvm install` command.

See [Install and switch between different versions of Node](#install-and-switch-between-different-versions-of-node) below for more information.
:::

::: details Step 3: Confirm Node alias is available

Confirm that the `node` alias works in WSL (and check your version) like so:

::: tabs#shell
@tab WSL (Bash)
```bash:no-line-numbers
node -v
```
:::

## Install and switch between different versions of Node

These options are applicable to the following setups:

1. [NVM for Windows](https://github.com/coreybutler/nvm-windows) standalone installation
2. Laravel Herd
3. NVM in WSL

Install a new version:

::: tabs#shell
@tab WSL (Bash)
```bash:no-line-numbers
nvm install lts/iron
```
@tab PowerShell
```powershell:no-line-numbers
nvm install lts/iron
```
:::

Change to a different installed version (example):
::: tabs#shell
@tab WSL (Bash)
```bash:no-line-numbers
nvm use lts/iron
```
@tab PowerShell
```powershell:no-line-numbers
nvm use lts/iron
```
:::

You can also refer to versions using their numbers rather than codenames. More information about the latest Node versions and their aliases can be found at at https://nodejs.org/en/about/releases/.

## <Badge type="info" text="Optional" vertical="middle" /> PhpStorm Configuration

See the [PhpStorm configuration notes](./phpstorm.md) for more information on configuring Node in PhpStorm.


