---
title: Bash/ZSH on Windows with WSL
---

# Windows Subsystem for Linux (WSL)

::: info
WSL = Windows Subsystem for Linux. It allows you to run a Linux terminal within Windows, which provides a Bash shell as opposed to the Command Prompt or
PowerShell.

This enables you to use Bash commands (which are much more common in web development documentation and tutorials than PowerShell or CMD), as well as tools like
the
like [Oh My ZSH](https://ohmyz.sh/) and plugins
like [autosuggestions](https://github.com/zsh-users/zsh-autosuggestions) and [syntax highlighting](https://github.com/zsh-users/zsh-syntax-highlighting).
:::

[[toc]]

## Installing WSL

You can install WSL [through PowerShell](https://learn.microsoft.com/en-us/windows/wsl/install) or through the Microsoft Store by finding your Linux
distribution of choice, such as [Debian](https://apps.microsoft.com/detail/9MSVKQC78PK6?hl=en-us&gl=AU&ocid=pdpshare)
or [Ubuntu](https://apps.microsoft.com/detail/9PDXGNCFSCZV?hl=en-us&gl=AU&ocid=pdpshare).

::: note
There are two versions of WSL: WSL1 and WSL2. WSL1 provides a simple terminal that integrates with the Windows filesystem and applications, whereas WSL2
provides a complete Linux kernel in a virtual machine.

All documentation in this site that refers to WSL has been written
while using WSL1 with [Debian](https://apps.microsoft.com/detail/9MSVKQC78PK6?hl=en-us&gl=AU&ocid=pdpshare). All commands are the same for Ubuntu, which is the
commonly documented option elsewhere.

You can fnd out more about the differences [in the Microsoft docs - Comparing WSL Versions](https://learn.microsoft.com/en-us/windows/wsl/compare-versions).
:::

## Using WSL

You can open your WSL terminal in the Windows Terminal app. You can set it as the default tab in Settings > Default profile.

If it isn't the default, you can select it in the new tab options:

![terminal-app.png](/terminal-app.png)

PhpStorm works very similarly, and you can set it to be your default terminal in Settings > Tools > Terminal by entering the path to `wsl.exe`:

![phpstorm-terminal.png](/terminal-phpstorm.png)

## Setting up PHP, Node, etc for use in the WSL terminal

The tool-specific guides in this site cover WSL usage options:
- [PHP on Windows](./php.md)
- [Node on Windows](./node.md)

The TL;DR is to add aliases to your `.bashrc` or `.zshrc` file to point to the Windows executables, for exmaple:

```bash
alias composer='powershell.exe /c C:\\Users\\LeesaWard\\.config\\herd\\bin\\composer.bat' # Use Windows Composer as set by Herd
alias php='powershell.exe /c C:\\Users\\LeesaWard\\.config\\herd\\bin\\php.bat' # Use Windows PHP as set by Herd
alias sass="powershell.exe sass -c \$(wslpath -w \$1)'" # Use Dart Sass installed via Chocolatey
```

You can also set PHP to use specific versions installed anywhere in Windows. For details and examples see the [PHP on Windows](./php.md) guide.
