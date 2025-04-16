---
title: Local dev setup
position: 1
showInPageNav: true
---

# Local development setup

:::important
This is the "decisions, not options" quick start guide which reflects the author's preferred setup, summarised here in the hope that it simplifies getting started on this multi-language project in the sea of options available.

Details on other options can be found in the [Local Dev Deep Dives](../local-dev-deep-dives/setup.md) section.
:::

:::warning
This guide is written for Windows users. MacOS and Linux users will need to slightly adapt some steps and file paths.
:::

[[toc]]

## Prerequisites
- Git installed on your machine
- [Chocolatey](https://chocolatey.org/) package manager installed on your machine
- Sufficient privileges to do the following on your machine:
	- install software on your machine
	- add entries to the hosts file
	- install certificates
	- add certificates to the trusted root store.

## Clone the repo

1. Create a directory in `C:\Users\<your-username>` called `PhpStormProjects`
2. Clone the repo using your GUI of choice (such as [GitKraken](https://www.gitkraken.com/)) or via your terminal (from the `PhpStormProjects` directory):

```powershell::no-line-numbers
git clone https://github.com/doubleedesign/comet-components.git
```

3. Create and check out a branch for the work you're going to do:

```powershell::no-line-numbers
git checkout -b <your-branch-name>
```

## Set up Laravel Herd and add the project

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

```powershell::no-line-numbers
Get-Command php
```
The output should list the source as `C:\Users\<your-username>\.config\herd\bin\php84\php.exe` or similar according to the global PHP version you have set in Herd.

:::details What if my PHP source is not Herd?
Go into your system environment variables and move the `PATH` entry for Herd to the top. Restart PowerShell and try again.
:::

4. Make sure Composer is installed and available:

```powershell::no-line-numbers
Get-Command composer
```

The output should list the source as `C:\Users\<your-username>\.config\herd\bin\composer.bat`.

5. Make sure Node is installed and available:

```powershell::no-line-numbers
Get-Command node
```
The output should list the source as `C:\Program Files\nodejs\node.exe` and the version should match the installed version listed in Herd.

If you install alternative versions in Herd, change the active version with:

```powershell::no-line-numbers
nvm use <version>
```

6. Tell Herd where the project is using one of the following methods:

- In the Herd GUI, go to `General` in the left menu, and add `PHPStormProjects` the `Herd Paths`. This will make Herd pick up all directories in `PHPStormProjects` as projects.
- From the Herd GUI Dashboard, click `Open Sites` and in the screen that appears, click `Add` and select the directory you cloned the repo into.
- From the `comet-components` directory your terminal, type `herd link`.

7. Enable HTTPS for the site by:

- going to the Herd GUI, clicking on the `Sites` tab, and enabling HTTPS for the site you just added, OR
- in your terminal from the project root, run `herd secure`.

This will create a self-signed certificate for you, which is suitable for local development.

:::details Why do I need HTTPS locally?
HTTPS is the standard in production environments and some tools and applications will not work correctly without it, even locally. Even if everything runs fine on HTTP locally, the difference can cause some headaches when you deploy to production, so enabling HTTPS locally can save time and effort later.
:::

8. Open https://comet-components.test in your web browser. It should load a local copy of these docs.

## Install Sass globally

1. Install the [Chocolatey](https://chocolatey.org) package manager for Windows if you don't already have it.
2. Install Dart Sass globally:

```powershell::no-line-numbers
choco install sass
```

## Install project dependencies

The project contains multiple sub-packages, and uses both [Composer](https://getcomposer.org/) and [NPM](https://www.npmjs.com/) to manage different types of dependencies.

A convenience script is provided to install all dependencies in the project root and all `packages`. You can run it from the project root (`comet-components` directory) with:

```powershell::no-line-numbers
npm run refresh:all:dev
```

This version uses `composer.local.json` if it exists, to symlink the core package to its usages in the other packages.

For a standard install, use:

```powershell::no-line-numbers
npm run refresh:all
```

:::warning
Some of the underling scripts are PowerShell scripts, which have not been tested in non-Windows environments. PowerShell for MacOS exists, but you may need to adapt the script for it to work; or alternatively replace it with a shell script. The source code of all the scripts can be found in the `scripts` directory in the project root.
:::

## Set up the IDE

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

1. Go to `File > Settings > Tools > Terminal`.
2. Tick `Use new terminal` to use the [JetBrains terminal](https://blog.jetbrains.com/idea/2024/02/the-new-terminal-beta-is-now-in-jetbrains-ides/).
3. In the `Start directory` field, enter the path to your project root (e.g., `C:\Users\<your-username>\PhpStormProjects\comet-components`).
4. In the `Shell path` field, select or enter the path to PowerShell.
5. Tick `Shell integration`.
6. Tick `Add default PHP interpreter to PATH` (it should already be there but it can't hurt to tick it).
7. Tick `Add node_modules/.bin` from the project root to PATH.

:::info
PhpStorm will pick up the version bundled with Windows, which at the time of writing is PowerShell 5.1. If you have installed Powershell 7 yourself, you can enter the path to the executable manually.

If you installed via the Windows Store this should be:

```:no-line-numbers
C:\Users\<your-username>\AppData\Local\Microsoft\WindowsApps\pwsh.exe
```
:::

:::tip Tip: Terminal colours
If you tick `Use new terminal`, the prompt and output will look different to a standalone PowerShell window. To customise the colours:

1. Go to `File > Settings > Editor > Color Scheme > Console Colors`.
2. In the list, expand `Block terminal` and go through the list of colours there to make your changes. **Note:** At the time of writing, these colours are _not_ shown in the preview below the list, and the ANSI colours, console colours, etc. defined there do not apply to the new terminal.

![Terminal colour settings](/phpstorm-terminal-colours.png)
:::

:::details Do I have to use PowerShell?
No, but you may need to adapt some commands and scripts for your shell of choice.
- CMD? Amateur hour - just use PowerShell, it comes with Windows.
- WSL? Hardcore, I like it. Instructions for almost all steps using WSL are available in the [Local setup deep dives](../local-dev-deep-dives/setup.md) section. (But be warned: A lot of them just route commands through PowerShell anyway, as integration of all tooling such as the IDE, PHP, Xdebug, Node, and Playwright is easier if you use the native Windows instances of things installed by Herd.)
  :::

![Terminal settings for PowerShell](/phpstorm-terminal-powershell.png)

### Node
Enable Node.js support in `File > Settings > Languages & Frameworks > Node.js`:
- In the `Node interpreter` dropdown, ensure `C:\Program Files\nodejs\node.exe` is selected.
- Tick `Coding assistance for Node.js`.

![Node settings](/phpstorm-node-windows.png)

### Scopes

Scopes are used to limit the scope of file watchers and other settings to specific directories. Configuration for the core package and the WordPress plugin is included in the `.idea` directory in the project root, so PhpStorm should pick them up automatically. If not, or you need to create a new scope for your implementation:

1. Go to `File > Settings > Appearance & Behaviour > Scopes`.
2. Click the `Add (+ plus sign)` button and select `Local`.
3. Enter a name for the scope (e.g., `Core Package`).
4. Select the directories you want to include in the scope (e.g., `packages/core`) and click `Include recursively`.
5. In the top right corner, tick `Share through VCS` to ensure the scope is included in the project's Git repository.

![Scope settings example](/phpstorm-scope-example.png)

### File watchers for asset compilation

:::info
File watcher configurations for Sass, Rollup, and Blade template formatting are included in the `.idea/watcherTasks.xml` file in the project root, so PhpStorm should pick them up automatically. The details are listed below for your understanding and future reference.
:::

:::details Sass
PhpStorm will probably prompt you to set up a file watcher when you open a `.scss` file for the first time, but you can also find or create the configuration in `File > Settings > Tools > File Watchers`.

To automatically compile individual SCSS files in the core package into CSS files in the same location (for example, `./src/components/global.scss` to `./src/components/global.css`), follow these steps:
1. Go to `File > Settings > Tools > File Watchers`.
2. Click the `Add (+ plus sign)` button and select `SCSS`.
3. In the `File Type` dropdown, select `SCSS style sheet`.
4. Uncheck `Track only root files`.
5. In the `Scope` dropdown, select `Core package`.
6. In the `Program` field, enter the path to the Sass executable that you installed in step 3:
```
C:\ProgramData\chocolatey\bin\sass
```
7. In the `Arguments` field, enter `--color --source-map $FileName$:$FileNameWithoutExtension$.css`.
8. In the `Output paths` field, enter `$FileNameWithoutExtension$.css:$FileNameWithoutExtension$.css.map`.
9. In the `Working directory` field, enter `$FileDir$`.
10. Under `Advanced Options`, tick `Auto-save edited files to trigger the watcher` and `Trigger the watcher on external changes`.
11. For `Show console`, select `On error`.
12. Click `OK` to save the watcher.

**Note:** The variables surrounded by `$` are variables that PhpStorm will replace with the actual file names when it runs the watcher. Do not replace these manually with your actual file names.

![Sass file watcher](/phpstorm-scss-filewatcher-core.png)
:::

:::details Rollup
Rollup is a JavaScript bundler that is used to bundle the core package's JavaScript into one file, to make it easier for implementations to use. It is installed with the project dependencies.

You can set up PhpStorm to automatically run Rollup when you save a JavaScript file like so:

1. Go to `File > Settings > Tools > File Watchers`.
2. Click the `Add (+ plus sign)` button and select `Custom`.
3. In the `File Type` dropdown, select `JavaScript`.
4. For the `Scope`, select `Core Package`.
5. In the `Program` field, enter the path to the Rollup executable:

```
$ProjectFileDir$\packages\core\node_modules\.bin\rollup
```
(Note: `$ProjectFileDir$` is a real variable that resolves to the project root directory, you don't need to manually replace that with your actual path.)

6. In the `Arguments` field, enter `-c` to use the configuration file in the project root.
7. In the `Output paths` field, enter `$ProjectFileDir$\packages\core\dist\dist.js` (this is the output file for the bundled JavaScript).
8. In the `Working directory` field, enter `$ProjectFileDir$\packages\core`.
9. Under `Advanced Options`, tick `Auto-save edited files to trigger the watcher` and `Trigger the watcher on external changes`.
10. For `Show console`, select `On error`.
11. Click `OK` to save the watcher.

![Rollup file watcher](/phpstorm-rollup-filewatcher.png)
:::

### Linting and formatting

A combination of tools and configurations are provided to ensure consistent code formatting across the project.

:::details PHP (General) - PhpStorm formatter
1. Go to `File > Settings > Editor > Code Style > PHP`;
2. Ensure the `Project` scheme is selected in the dropdown at the top.
3. Go to `File > Settings > Actions on save`.
4. Tick `Reformat code`.
5. In the `Reformat code` option, open the `File types` list and **untick** Blade, JavaScript, and TypeScript. Ensure PHP *is* ticked.

[![PHPStorm PHP code style settings](/phpstorm-php-codestyle.png)
:::

:::details Blade templates - Blade formatter
[Blade Formatter](https://github.com/shufo/blade-formatter) is a third-party tool installed as a core package dependency, with configuration defined in the `./packages/core/.bladeformatterrc.json` file.

To configure PhpStorm to automatically format Blade templates on save, follow these steps:
1. Go to `File > Settings > Tools > File Watchers`.
2. Click the `Add (+ plus sign)` button and select `Custom`.
3. In the `File Type` dropdown, select `Blade`.
4. For the `Scope`, select `Core Package`.
5. In the `Program` field, enter `powershell.exe`.
6. In the `Arguments` field, enter:
```powershell:no-line-numbers
"$ProjectFileDir$\packages\core\node_modules\.bin\blade-formatter.ps1" --write "$FilePath$"
```
**Note:** `$ProjectFileDir$` is a variable that PhpStorm will replace with the real path when it runs the watcher. You not replace this manually with your actual path.

7. In the `Output paths to refresh` field, enter `$FileName$`.
8. In the `Working directory` field, enter `$FileDir$`.
9. Under `Advanced Options`, tick `Auto-save edited files to trigger the watcher` and `Trigger the watcher on external changes`.
10. For `Show console`, select `On error`.
11. Click `OK` to save the watcher.

![Blade file watcher](/phpstorm-blade-filewatcher-powershell.png)
:::

:::details JavaScript and TypeScript - ESLint
[ESLint](https://eslint.org/) is installed as a project dependency and configuration for linting and formatting JavaScript and TypeScript code is defined in `./eslint.config.js`.

Configure PhpStorm to run ESLint on save and fix issues automatically where possible by following these steps:
1. Go to `File > Settings > Languages & Frameworks > JavaScript > Code Quality Tools > ESLint`.
2. Select `Automatic ESLint configuration`.
3. Tick `Run eslint --fix on save`.

:::

### Pest

Configuration of Pest/PHPUnit is mostly handled in its configuration file (`./test/phpunit.xml`) and the Run configuration.

You can check and tweak the default settings for PhpStorm under `File > Settings > Languages & Frameworks > PHP > Test Frameworks` (shown below) and for the run configuration under `Run > Edit Configurations`.

![Pest settings](/phpstorm-pest.png)

## Update the `php.ini` file

Locate the PHP configuration file (as per the PHP interpreter settings above) and add the following lines to it (updated with your username):

```ini
herd_auto_prepend_file = C:/Users/YOUR_USERNAME/PHPStormProjects/comet-components/test/browser/wrapper-open.php
herd_auto_append_file = C:/Users/YOUR_USERNAME/PHPStormProjects/comet-components/test/browser/wrapper-close.php
```
This is to wrap test pages with the required opening and closing HTML.

## Ensure test pages load

Navigate to a test page such as http://comet-components.test/test/browser/pages/container-colours.php in your browser. You should see the page content with styling applied.

## Set up Playwright

Playwright itself should be installed in the project by the refresh script listed above, but you may need to install the browsers it uses.

1. To install a browser for Playwright, in the location it expects to find it, with the following command:

```powershell::no-line-numbers
npx playwright install firefox
```

2. Ensure the `BROWSER_TEST_URL` in the `.env` file in the project root is set to `http://comet-components.test`.

3. In PhpStorm, ensure it understands Playwright for syntax highlighting and code completion by following these steps:

- add Playwright to the JS libraries under `Settings > Languages & Frameworks > JavaScript > Libraries`. Include all of `@playwright/test`, `playwright`, and `playwright-core`.
- In `Settings > Languages & Frameworks > TypeScript`, uncheck `use types from server`.

## Set up and run Storybook

The refresh script should have installed the NPM dependencies for Storybook, but you will also need a local self-signed certificate so that it can run over HTTPS. You can generate this easily with the [OpenSSL](https://www.openssl.org/) command line utility.

:::details Why do I need HTTPS locally?
HTTPS is the standard in production environments and some tools and applications will not work correctly without it, even locally. For example, Storybook loading the test pages into iframes can have problems over HTTP. And as for why we enable HTTP in Herd for the local development site, because HTTPS is standard for production environments it can save us some headaches later if we develop locally with the same standard.
:::

To check if OpenSSL is available on your machine already, run:

```powershell:no-line-numbers
Get-Command openssl
```

If it isn't, you can install it with [Chocolatey](https://community.chocolatey.org/packages/OpenSSL) (from a PowerShell instance with admin privileges):
```powershell:no-line-numbers
choco install openssl
```

Then back in your main terminal from the project root, run the convenience script that will:
- add an entry to the hosts file to enable Storybook to use the domain `storybook.comet-components.test`
- generate a self-signed cert for Storybook and add it to the trusted root store in Windows
- ensure the Herd project certificate is also in the trusted root store in Windows

```powershell:no-line-numbers
./scripts/local-hosts-and-certs.ps1
```

The script will provide feedback about its success or failure, but you can also check manually in the Certificate Manager.
1. Access the Certificate Manager via the Start menu (search for "Certificate" and choose "Manage user certificates") or via the Run dialog (Windows + R) -> `certmgr.msc`
2. In the left pane, expand `Trusted Root Certification Authorities` and select `Certificates`
3. Look for the following two certificates:

```text
| Issued to                       | Issued by                       | Intended purpose      |
|---------------------------------|---------------------------------|-----------------------|
| storybook.comet-components.test | storybook.comet-components.test | Server authentication |
| comet-components.test           | Laravel Valet CA Self-Signed CN | <All>                 |
```

Ensure that the Herd project site loads as secure by navigating to a test component URL, such as https://comet-components.test/packages/core/src/components/Container/__tests__/container.php.

Next, you can run Storybook locally with:
```powershell::no-line-numbers
npm run storybook
```
Use the "network" URL to access it: https://storybook.comet-components.test:6006/ and ensure that your browser sees the connection as secure.

:::details Cross-origin (CORS) errors
The project is configured to allow Storybook to request the test pages from the local development site in `./browser/test/wrapper-open.php`, which you should have configured `php.ini` to use as the `herd_auto_prepend_file` in step 7 above. If you still have CORS problems, there are a number of browser extensions you can use to work around it.
:::
