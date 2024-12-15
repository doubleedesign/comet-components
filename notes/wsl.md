# My WSL setup

WSL = Windows Subsystem for Linux. It allows you to run a Linux terminal within Windows, which provides a Bash shell as opposed to the Command Prompt or PowerShell.

There are two versions of WSL: WSL1 and WSL2. Because I just want it as a simple terminal that integrates with my Windows filesystem and applications, I use WSL1. All notes/docs/config/scripts throughout this project reflect this. 

You can fnd out more about the differences [in the Microsoft docs - Comparing WSL Versions](https://learn.microsoft.com/en-us/windows/wsl/compare-versions).

## How?

You can install WSL [through PowerShell](https://learn.microsoft.com/en-us/windows/wsl/install) or through the Microsoft Store by finding your Linux distribution of choice. Mine is [Debian](https://www.microsoft.com/store/productId/9MSVKQC78PK6?ocid=pdpshare); Ubuntu is also a popular choice (possibly more so).

## Why?

- A lot of developer tutorials and documentation use Bash (presumably because of the prevalence of MacOS users amongst us + many web servers running on Linux). This can save you a translation step in a lot of situations.
- You can customise it! For example, I use [Oh My ZSH](https://ohmyz.sh/) with the Git, [autosuggestions](https://github.com/zsh-users/zsh-autosuggestions/blob/master/INSTALL.md), and [syntax highlighting](https://github.com/zsh-users/zsh-syntax-highlighting/blob/master/INSTALL.md) plugins, which give me a real productivity boost.
