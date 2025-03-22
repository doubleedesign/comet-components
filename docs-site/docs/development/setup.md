# Local development setup

[[toc]]

## Prerequisites

- PHP and [Composer](https://getcomposer.org) installed locally
- [Node](https://nodejs.org) installed locally
- Git installed locally
- [Sass](https://sass-lang.com) installed globally on your machine
- IDE of choice (I use [PhpStorm](https://www.jetbrains.com/phpstorm/))

:::note
The author of Comet Components is a Windows user and [PhpStorm](https://www.jetbrains.com/phpstorm/) is her IDE of choice. While many of the instructions in
this documentation and the convenience scripts provided are platform-agnostic (as she uses [WSL](https://learn.microsoft.com/en-us/windows/wsl/) which provides
a Bash terminal, and many of the scripts are written in PHP or TypeScript), there are some things that developers using MacOS, Linux, and/or non-JetBrains IDEs
will need to
adapt for their own environments.
:::

:::tip
Windows-specific setup options and instructions are listed in the [Windows setup deep-dives](#windows-setup-deep-dives) section below.
:::

## Quick start

1. Clone the repository from GitHub
2. Install dependencies, refresh autoloading, and redo symlinks:

::: tabs#shell
@tab Bash
```bash:no-line-numbers
npm run refresh:all
```
@tab PowerShell
```powershell:no-line-numbers
npm run refresh:all
```
:::

See the [CLI command quick reference](./appendices/cli-commands.md) for more options if you are returning to
an already set up copy of the project and don't need to do a full refresh.

3. Run the local web server and Storybook (at the same time i.e. two terminal windows) to see what you're working with!

::: tabs#shell
@tab Bash
```bash:no-line-numbers
npm run test:server
```
```bash:no-line-numbers
npm run test:storybook
```
@tab PowerShell
```powershell:no-line-numbers
npm run test:server
```
```powershell:no-line-numbers
npm run test:storybook
```
:::

4. Optionally, run these docs locally:

::: tabs#shell
@tab Bash
```bash:no-line-numbers
npm run docs
```
@tab PowerShell
```powershell:no-line-numbers
npm run docs
```
:::

## Windows setup deep-dives
