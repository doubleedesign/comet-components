---
title: Local dev quick start
position: 1
---

# Local development setup

:::important
This is the "decisions, not options" quick start guide which reflects the author's preferred setup, summarised here in the hope that it simplifies getting started on this multi-language project in the sea of options available.

Details on other options can be found in the [Local Dev Deep Dives](../local-dev-deep-dives/setup.md) section.
:::

:::warning
This guide is written for Windows users. MacOS and Linux users will need to adapt some steps.
:::

[[toc]]

## 0. Prerequisites
- Git installed locally
- Windows machine with sufficient privileges to create symbolic links using PowerShell
  - _or_ the ability to translate/adapt PowerShell commands and scripts for your OS
- Sufficient privileges to install software on your machine.

## 1. Clone the repo

1. Create a directory in `C:\Users\<your-username>` called `PhpStormProjects`
2. Clone the repo using your GUI of choice (such as [GitKraken](https://www.gitkraken.com/)) or via your terminal (from the `PhpStormProjects` directory):

```powershell
git clone https://github.com/doubleedesign/comet-components.git
```

3. Create and check out a branch for the work you're going to do:

```powershell
git checkout -b <your-branch-name>
```

## 2. Set up Laravel Herd

Laravel Herd is an all-in-one local development environment tool for PHP and Node. It takes the place of the likes of WAMP, MAMP, or XAMPP for PHP, and Node Version Manager (NVM) for Node, while also providing [Composer](https://getcomposer.org/) and [Xdebug](https://xdebug.org/) out of the box.

1. If you already have Node installed on your machine, remove it so Herd can install the version of NVM it requires and thus manage Node for you. (Other instances of PHP can stay if you want.)
2. Download and install [Laravel Herd Pro](https://herd.laravel.com/)

:::details Do I really need to pay for Pro?
No, but without Pro you won't have built-in Xdebug, the Dumps feature, or database services.
- Xdebug is required for generating unit test coverage reports
- Dumps is a great debugging tool which is referenced in this documentation
- Database services are required if you want to use Herd to develop websites with WordPress or any other database-driven CMS.
:::

3. Make sure Herd is controlling PHP:

```powershell
Get-Command php
```
The output should list the source as `C:\Users\<your-username>\.config\herd\bin\php84\php.exe` or similar according to the global PHP version you have set in Herd.

:::details What if my PHP source is not Herd?
Go into your system environment variables and move the `PATH` entry for Herd to the top. Restart PowerShell and try again.
:::

4. Make sure Composer is installed and available:

```powershell
Get-Command composer
```

The output should list the source as `C:\Users\<your-username>\.config\herd\bin\composer.bat`.

5. Make sure Node is installed and available:

```powershell
Get-Command node
```
The output should list the source as `C:\Program Files\nodejs\node.exe` and the version should match the installed version listed in Herd.

If you install alternative versions in Herd, change the active version with:

```powershell
nvm use <version>
```

6. Tell Herd where the project is using one of the following methods:
 - In the Herd GUI, go to `General` in the left menu, and add `PHPStormProjects` the `Herd Paths`. This will make Herd pick up all directories in `PHPStormProjects` as projects.
 - From the Herd GUI Dashboard, click `Open Sites` and in the screen that appears, click `Add` and select the directory you cloned the repo into.
 - From the `comet-components` directory your terminal, type `herd link`.

7. Open http://comet-components.test in your web browser. It should load a local copy of these docs.

## 3. Install Sass globally

1. Install the [Chocolatey](https://chocolatey.org) package manager for Windows if you don't already have it.
2. Install Dart Sass globally:

```powershell
choco install sass
```

## 4. Install project dependencies and create symlinks

The project contains multiple sub-packages, and uses both [Composer](https://getcomposer.org/) and [NPM](https://www.npmjs.com/) to manage different types of dependencies. In addition, symbolic links (symlinks) are used to make certain files available in certain locations for easy browser testing. 

A convenience script is provided to install all dependencies in the project root and all `packages`, and create symlinks for the `test` directory. You can run it from the project root (`comet-components` directory) with:

```powershell
npm run refresh:all
```
:::warning
Some of the underling scripts are PowerShell scripts, which have not been tested in non-Windows environments. PowerShell for MacOS exists, but you may need to adapt the script for it to work; or alternatively replace it with a shell script. The source code of all the scripts can be found in the `scripts` directory in the project root.
:::

## 5. Set up the IDE

[PhpStorm](https://www.jetbrains.com/phpstorm) is a powerful, fully-featured IDE for PHP and JavaScript development and testing. Download and install it.

:::details Do I really have to use PhpStorm? What about VSCode?
No, you don't have to use PhpStorm, but all IDE-specific information in these docs is written for it.
:::

### Plugins
Open PhpStorm and go to `File > Settings > Plugins`.

:::details Plugins to install and enable
Install and activate the following plugins and any dependencies they have:
   - [Test Automation](https://plugins.jetbrains.com/plugin/20175-test-automation)
   - [PHP Annotations](https://plugins.jetbrains.com/plugin/7320-php-annotations)
   - [PowerShell](https://plugins.jetbrains.com/plugin/10249-powershell)

In `File > Settings > Plugins`, ensure the following bundled plugins are enabled:
   - PHP
   - PHP Architecture 
   - JavaScript and TypeScript
   - Node.js
   - Vue.js
   - Ini
   - JSON
   - CSS
   - Sass
   - Blade
   - Pest
   - Terminal
:::

### PHP Interpreter and Xdebug
1. In `File > Settings > PHP`, click the 3 dot button next to the `CLI Interpreter` dropdown. 
2. Add the Path to Herd's PHP `.bat` file so that it will use the global PHP version set in Herd. 
3. Click the refresh button and make sure it has picked up the version, configuration file, and Xdebug correctly. If it does not detect Xdebug, you can manually add the path to it as shown in the below screenshot.

![PHP interpreter settings](/phpstorm-php-bat.png)

### Terminal

Go to `File > Settings > Tools > Terminal` and:
 - in the `Shell path` field, select or enter the path to PowerShell
 - tick `Shell integration`
 - tick `Add default PHP interpreter to PATH` (it should already be there but it can't hurt to tick it)
 - tick `Add node_modules/.bin` from the project root to PATH.

:::details Do I have to use PowerShell?
No. CMD? Amateur, just use PowerShell. WSL? Hardcore, I like it. Instructions for almost all steps using WSL are available in the [Local setup deep dives](../local-dev-deep-dives/setup.md) section. (But be warned: A lot of them just route commands through PowerShell anyway, as integration of all tooling such as the IDE, PHP, Xdebug, Node, and Playwright is easier if you use the native Windows instances of Node, PHP, etc. installed by Herd.)
:::

### Node
Enable Node.js support in `File > Settings > Languages & Frameworks > Node.js`:
- In the `Node interpreter` dropdown, ensure `C:\Program Files\nodejs\node.exe` is selected.
- Tick `Coding assistance for Node.js`.

### File watchers

Follow the instructions in the [PhpStorm page](../local-dev-deep-dives/tooling-guides/phpstorm.md#file-watchers) to set up file watchers for Sass, Rollup, and the Blade template formatter.

### Linting and formatting

Configure automatic linting and formatting with a combination of PhpStorm's built-in tools and ESLint as per the instructions in the [PhpStorm page](../local-dev-deep-dives/tooling-guides/phpstorm.md#linting-and-formatting).

### Pest (for unit testing)

Configuration of Pest/PHPUnit is mostly handled in its configuration file (`./test/phpunit.xml`) and the Run configuration.

You can check and tweak the default settings for PhpStorm under `File > Settings > Languages & Frameworks > PHP > Test Frameworks` (shown below) and for the run configuration under `Run > Edit Configurations`.

![Pest settings](/phpstorm-pest.png)

## 6. Update the `php.ini` file

Locate the PHP configuration file (as per the PHP interpreter settings above) and add the following lines to it (updated with your username):

```ini
herd_auto_prepend_file = C:/Users/YOUR_USERNAME/PHPStormProjects/comet-components/test/browser/wrapper-open.php
herd_auto_append_file = C:/Users/YOUR_USERNAME/PHPStormProjects/comet-components/test/browser/wrapper-close.php
```
This is to wrap test pages with the required opening and closing HTML.

## 7. Ensure test pages load

Navigate to a test page such as http://comet-components.test/test/browser/pages/container-colours.php in your browser. You should see the page content with styling applied.

## 8. Set up Playwright (for integration testing)

Playwright itself should be installed in the project by the refresh script listed above, but you may need to install the browsers it uses.

1. To install a browser for Playwright, in the location it expects to find it, with the following command:

```powershell
npx playwright install firefox
```

2. Ensure the `BROWSER_TEST_URL` in the `.env` file in the project root is set to `http://comet-components.test`.

3. In PhpStorm, ensure it understands Playwright for syntax highlighting and code completion by following these steps:
  - add Playwright to the JS libraries under `Settings > Languages & Frameworks > JavaScript > Libraries`. Include all of `@playwright/test`, `playwright`, and `playwright-core`.
  - In `Settings > Languages & Frameworks > TypeScript`, uncheck `use types from server`.

## 9. Run Storybook

The refresh script should have installed the dependencies for Storybook. Run it locally with:

```powershell
npm run storybook
```
