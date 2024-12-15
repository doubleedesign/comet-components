# Node development setup on Windows

- [Local Node Installation](#local-node-installation)
  - [Installing Node natively in Windows](#installing-node-natively-in-windows)
  - [Confirm Node alias is available](#confirm-node-alias-is-available)
  - [Optional Use Windows' Node in WSL](#optional-use-windows-node-in-wsl)
- [Optional] [Installing Node in WSL](#installing-node-in-wsl)
  - [Installing NVM](#installing-nvm)
  - [Installing Node](#installing-node)
  - [Confirm Node alias is available](#confirm-node-alias-is-available)
- [Install and switch between different versions of Node](#install-and-switch-between-different-versions-of-node)
- [Optional] [PHPStorm Configuration](#optional-phpstorm-configuration)

---
## Local Node installation

### Installing Node natively in Windows

There are a number of ways you can install Node natively in Windows, including:
1. Downloading and running the installer from [nodejs.org](https://nodejs.org) (quickest and easiest in the short term, but not great for updates or switching between versions easily)
2. Using the [Chocolatey](https://community.chocolatey.org/) package manager through PowerShell[^1]
3. Using [NVM for Windows](https://github.com/coreybutler/nvm-windows) (preferred for if you need the ability to switch between Node versions)
4. [Laravel Herd](https://herd.laravel.com/windows) also includes Node[^2].

For ease of version management, I recommend using NVM for Windows (if you prefer the command line) or Laravel Herd (if you prefer a GUI and are already using it for PHP and Composer).

> [!WARNING]
> Unlike for [PHP](./php.md) where you can have multiple instances from Chocolatey, Herd, Local, and other web server applications installed concurrently without issue, you can't use Herd to manage Node if you have NVM for Windows installed independently. (Not sure about Chocolatey.) So if you have NVM but want to use Herd for Node version management now, you'll need to uninstall NVM first and then re-install it through the Herd UI.

In any case, once you've installed Node using your method of choice, all commands will be the same from there other than upgrading or switching between versions.

### Confirm Node alias is available

Confirm that the `node` alias works in PowerShell (and check your version) like so: 
```PowerShell
node -v
```

If this doesn't work, you may need to manually add Node to your [PATH system environment variable](./path.md). 

To check where PowerShell is loading Node from if you have multiple instances, you can use:
```PowerShell
Get-Command node
```

### [Optional] Use Windows' Node in WSL

Like for [Composer](./php.md#optional-use-windows-composer-in-wsl), you can use Windows' Node installation in WSL by creating aliases to Node and NPM in your Bash configuration file (`.bashrc` or `.zshrc`) like so:

```bash
# # /home/leesa/.zshrc
alias node='/mnt/c/Program\ Files/nodejs/node.exe'
alias npm='powershell.exe /c npm'
```

Restart the WSL terminal before trying to use these aliases.

---
### Installing Node in WSL

Unlike [for PHP](./php.md), I haven't had any pressing need to alias Windows' Node installation to WSL - I just install it in WSL using its own version of [NVM](https://github.com/nvm-sh/nvm?tab=readme-ov-file#installing-and-updating). (But if I had discovered Herd before that maybe I would have, because Herd is very convenient!)

PHPStorm's various configuration options that involve Node pick up WSL's Node instance automatically, so ensuring I'm using the same instance across the tools I need is generally straightforward once Node is installed.

The only thing to note about Windows vs WSL Node instances for this particular project is when using the `BlockTransformer` class in tests: if using Windows' PHP instance, then the PHP `shell_exec` function will use Windows' Node instance. Generally this is fine if the versions are compatible, but it's good to be aware that that's how it works. If you want to use the Windows Node instance because of this, see above for how to set up the aliases in your WSL terminal and skip the rest of this section.

#### Installing NVM

Instructions for installing NVM on WSL can be found in:
- [Microsoft's WSL Docs > Install NVM, Node.js and NPM](https://learn.microsoft.com/en-us/windows/dev-environment/javascript/nodejs-on-wsl#install-nvm-nodejs-and-npm)
- The [NVM GitHub repo](https://github.com/nvm-sh/nvm?tab=readme-ov-file#installing-and-updating).

#### Installing Node

Once you have NVM installed within WSL (_not_ referring to Windows NVM here, if you have it), you can install the LTS (long-term support) version of Node that I've been using like so:
```bash
nvm install lts/iron   
```
To switch to this version, use:
```bash
nvm use lts/iron
```

You can also refer to versions using their numbers rather than codenames. More information about the latest Node versions and their aliases can be found at at https://nodejs.org/en/about/releases/.

#### Confirm Node alias is available

Confirm that the `node` alias works in WSL (and check your version) like so: 
```bash
node -v
```

---
## Install and switch between different versions of Node

These options are applicable to the following setups:
- [NVM for Windows](https://github.com/coreybutler/nvm-windows) standalone installation
- Laravel Herd
- NVM in WSL

Install a new version:
```bash
nvm install <version>
```

Change to a different installed version:
```bash
nvm use <version>
```

---
## [Optional] PHPStorm Configuration

Ensure that PHPStorm's default Node interpreter matches the one you are using in your terminal. You can find this in Settings > Languages & Frameworks > Node.js.

![phpstorm-node.png](images/phpstorm-node.png)

If this is not set correctly, tools like ESLint won't work in the editor.

---
[^1]: Chocolatey is great for a lot of things, but I wouldn't recommend it for Node unless you don't expect to ever need to switch between Node versions easily.
[^2]:  Herd is a good option if you don't have any pre-existing stuff that might conflict, or are happy to use it for all Node management in Windows.
