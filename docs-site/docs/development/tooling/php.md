---
title: PHP on Windows
---

# PHP development setup on Windows

[[toc]]

## Installing PHP natively in Windows

You can install PHP in Windows a number of ways, including:

1. Installing [Laravel Herd](https://herd.laravel.com/windows), a GUI which includes PHP and Composer and puts them in your [system PATH](../setup.md#general-notes-and-troubleshooting-tips) automatically
2. Using the [Chocolatey](https://community.chocolatey.org/) package manager through PowerShell
3. For WordPress development, using [Local by Flywheel](https://localwp.com/)
4. Using local web server software such as [WampServer](https://www.wampserver.com/en/)
5. Downloading a zip from [php.net](https://www.php.net/downloads) and extracting it where you want it to live (quickest and easiest in the short term, but not great for updates).

:::details Install with Laravel Herd
Download and install [Laravel Herd](https://herd.laravel.com/windows). It comes with PHP and Composer built in, and makes it very easy to have multiple PHP versions installed and switch between them - no need to change environment variables or even type a terminal command.
:::
:::details Install via PowerShell with Chocolatey
Standard installation - installs in C:/tools by default:
   ```powershell:no-line-numbers
   choco install php 
   ```

To update:
   ```powershell:no-line-numbers
   choco upgrade php
   ```
:::

After installing, you can confirm PHP is available in your terminal (and the version) like so:

::: tabs#shell
@tab PowerShell
```powershell:no-line-numbers
php -v
```
:::

If this doesn't show a PHP version, you may just need to manually [add PHP to your PATH](../setup.md#general-notes-and-troubleshooting-tips).

If you have multiple instances of PHP, see which is in use with:

::: tabs#shell
@tab PowerShell
```powershell:no-line-numbers
Get-Command php
```
:::

---
:::info <Badge type="info" text="Optional" vertical="middle" /> Use Windows' PHP in WSL

If using [WSL](./wsl.md) for your day-to-day CLI needs, you can install PHP within the Linux environment, but for consistency with other tools it can be easier to use the Windows installation. There are two ways you can do this:
:::

:::details Option 1: Use an alias

You can have WSL use the "Global PHP version" set in Laravel Herd using an alias in `.zshrc` or `.bashrc` that routes the command via PowerShell like the below example:

```bash
# /home/leesa/.zshrc
alias php='powershell.exe /c C:\\Users\\leesa\\.config\\herd\\bin\\php.bat'
```

You can also alias it to a specific version or instance of PHP, for example:

```bash
# /home/leesa/.zshrc
# Specific Herd instance
alias php='powershell.exe /c C:\\Users\\leesa\\.config\\herd\\bin\\php84\\php.exe'
```
```bash
# /home/leesa/.zshrc
# Chocolatey default location
alias php='powershell.exe /c C:\\tools\\php84\\php.exe'
```

Restart the WSL terminal and then confirm it works and see the version with:

```bash
php -v
```
:::

:::details Option 2: Use a symlink

You can create a symbolic link to have WSL use a specific PHP executable that is installed anywhere in Windows.

If you already have one set up and are here to change your PHP version, remove the existing symlink first:

```bash
sudo rm /usr/local/bin/php
```

For a symlink to specific PHP version, use one of the following from a WSL terminal as relevant to your setup and PHP version, the command format is `sudo ln -s /mnt/c/path/to/php.exe /usr/local/bin/php`. For example:

```bash
# For PHP 8.4 from Laravel Herd (replace leesa with your Windows username)
sudo ln -s /mnt/c/Users/leesa/.config/herd/bin/php84/php.exe /usr/local/bin/php
```

Restart the WSL terminal and then confirm it works and see the version with:

```bash
php -v
```
:::

:::details Troubleshooting

To confirm which PHP instance is being used, you can run the following in WSL:

```bash:no-line-numbers
which php
```

If it is using alias, you will see something like:

```bash
php: aliased to powershell.exe /c C:\Users\leesa\.config\herd\bin\php.bat
```

For a symlink, you will see something like:

```bash
/usr/local/bin/php
```

If you have both, the alias will take precedence.
:::

## Composer

Composer is a dependency manager for PHP. You can install it in a number of ways, such as:

- Downloading and running the [Windows installer](https://getcomposer.org/download/) from the Compser website
- Via Chocolatey in PowerShell
- By installing [Laravel Herd](https://herd.laravel.com/windows), which comes with Composer built-in.

:::details Install with Laravel Herd
Download and install [Laravel Herd](https://herd.laravel.com/windows). It comes with PHP and Composer built in.
:::
:::details Install via PowerShell with Chocolatey

```PowerShell
choco install composer
```

To update:

```PowerShell
choco upgrade composer
```
:::

:::details Confirm Composer alias is available

Once installed, confirm that it works in PowerShell:

```powershell:no-line-numbers
composer -v
```

If it doesn't work, you probably just need to manually add the path to composer in your [PATH system environment variable](../setup.md#general-notes-and-troubleshooting-tips).
:::

:::info <Badge type="info" text="Optional" vertical="middle" /> Use Windows' Composer in WSL

If using Windows' PHP as explained above, Composer can then be used from WSL by adding an alias to your Bash config (
`.bashrc` or `.zshrc`) like so:

```bash
# /home/leesa/.zshrc
alias composer='powershell.exe /c C:\\Users\\leesa\\.config\\herd\\bin\\composer.bat'
```

As you can see from it starting with `powershell.exe`, this effectively makes WSL a wrapper and the command is actually
executed by PowerShell. This makes no real difference in practice, but it's nice to not have to switch terminals.

Restart the WSL terminal and then confirm it works:

::: tabs#shell
@tab WSL (Bash)
```bash:no-line-numbers
composer -v
```
:::

---

## Checking and changing instances

:::details Which instance is being used?

At any time, you can confirm where the PHP and Composer aliases resolve to with the following commands:

::: tabs#shell
@tab WSL (Bash)
```bash:no-line-numbers
readlink -f $(which php)
```
```bash:no-line-numbers
which composer
```
@tab PowerShell
```powershell:no-line-numbers
Get-Command php
```
```powershell:no-line-numbers
Get-Command composer
```
:::

:::details Changing the PHP or Composer instance
To change the global PHP version, if you're using Laravel Herd you can just do it in the GUI - there's a simple dropdown. Otherwise, you can modify the [system PATH variables](../setup.md#general-notes-and-troubleshooting-tips) in the Windows GUI.

For WSL, follow the "Use Windows' PHP in WSL" and "Use Windows' Composer in WSL" instructions above to set up or update your alias or symlink to the PHP executable you want to use.
:::

## <Badge type="info" text="Optional" vertical="middle" />  Xdebug

Xdebug is a PHP extension which provides debugging and profiling capabilities. It is required for generating code coverage reports with PHPUnit, and is useful for other debugging tasks.

:::tip
If you are using Laravel Herd or Local by Flywheel to manage PHP, Xdebug is already installed on your system.

If using PhpStorm you can check if Xdebug is available (and find your `php.ini` file if it isn't) in `File > Settings > PHP > CLI Interpreter`.

:::

For Local by Flywheel, there is a toggle on your site's main screen to enable it. For other setups, enable it by adding the following to the `php.ini` file (updating the path to the Xdebug DLL as necessary):

```ini
zend_extension = C:\Program Files\Herd\resources\app.asar.unpacked\resources\bin\xdebug\xdebug-8.4.dll
xdebug.mode = debug,develop
xdebug.start_with_request = yes
xdebug.start_upon_error = yes
```

:::details Tips for using XDebug with Local by Flywheel
If you are trying to use XDebug for a WordPress site using Local, make sure to:

- Turn on XDebug in the Local GUI for the site
- Set the PhpStorm CLI interpreter to use Local's PHP instance
- Exit Laravel Herd if it is running, because it is probably using the same port unless you've changed one of them
- Restart your site in Local after making any changes to `php.ini`.

:::

---

## <Badge type="info" text="Optional" vertical="middle" /> PhpStorm Configuration

See the [PhpStorm setup notes](./phpstorm.md) for more information.
