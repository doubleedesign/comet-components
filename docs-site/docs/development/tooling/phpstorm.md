---
title: PhpStorm
---

# PhpStorm development environment setup

:::important
Ensure you have [PHP](./php.md) and [Node](./node.md) set up locally before continuing.
:::

[[toc]]

## PHP interpreter

For IDE tools such as the PHPUnit test runner and Xdebug integration to work, you will need to set the PHP interpreter and language version. For consistency, usually this should be the same as the PHP instance you are using in your terminal.

You can find these settings in PhpStorm under `File > Settings > Languages & Frameworks > PHP`. If your PHP instance is not listed, click the 3 dots next to the dropdown and add it.

Below is an example of adding Laravel Herd's PHP 8.4 instance:

![PHP interpreter settings](/phpstorm-php.png)

Generally, it should pick up the configuration file (`php.ini`) automatically, but if you have some custom setup or problems with that you can set the path to your preferred file in the field provided in this dialog.

## Xdebug

If you have [Xdebug](https://xdebug.org/) installed and enabled in your PHP instance, PhpStorm will use it for debugging and code coverage reporting from unit tests. You can check if it is available in the interpreter settings as per the above.

## Node

Ensure that PhpStorm's default Node interpreter matches the one you are using in your terminal. If this is not set correctly, tools like ESLint won't work in the editor and you may experience inconsistent or unexpected behaviour when switching between doing tasks in the terminal and the IDE.

You can find the setting for this in `Settings > Languages & Frameworks > Node.js`.

![Node settings](/phpstorm-node.png)

## File watchers

:::info
These configurations are included in this repository (`.idea/watcherTasks.xml`) so it _should_ all be there already. The below is a guide on how to set them up or edit them if necessary.
:::

:::warning
If you add a new JS file, you need to add it to `rollup.index.js` so that Rollup knows to include it in the bundle. The watcher is not aware of new files automatically.
:::

:::details Sass

If you have installed Sass globally on your machine, you can set up a file watcher in PhpStorm to compile your SCSS to
matching CSS files. This is particularly useful if using pure Dart Sass installed via Chocolatey in Windows. (Also, it's
just neat to not have to run a command at all.)

PhpStorm will probably prompt you to set up a file watcher when you open a `.scss` file for the first time, but you can
also find the configuration in `File > Settings > Tools > File Watchers`. An example config looks like this:

![Sass file watcher](/phpstorm-scss-filewatcher.png)

At the time of writing this, two Sass watchers are included the provided configuration:

1. To compile the core package's individual components' SCSS files to CSS files in the same directory
2. To compile the `blocks.scss` file in the WordPress plugin package, which imports files from the core package; the
   watcher also watches those.

The difference between the two is that the core one is scoped to a custom scope of just that package folder (you can
create this under `Files to watch -> Scope ->` click the 3 dots), whereas the WordPress plugin one is scoped to the
entire project so that the core package's files are also watched.
:::

:::details Rollup (JavaScript bundling)

If you are working on the core package, you can set up a file watcher to bundle the component JS files into a single file automatically on save.

In `File > Settings > Tools > File Watchers`:

![Rollup file watcher](/phpstorm-rollup-filewatcher.png)

:::

## Linting and formatting

:::details PHP (General)
A standalone formatter has not been configured for PHP in this project (other than Blade templates as per the file watcher detailed above). The author's preferred configuration is included in the repository for PhpStorm's built-in formatter. You can find these settings in `File > Settings > Editor > Code Style > PHP`, selecting `Project` in the `Scheme` dropdown at the top should you need to modify them.

You can enable enforcing formatting rules on save in `File > Settings > Tools > Actions on save` by ticking the `Reformat code` option.

:warning: Important: Ensure JavaScript and TypeScript are not ticked in the list of file types to reformat on save, to avoid conflicts with ESLint.

![Actions on save.png](/phpstorm-save.png)
:::

:::details PHP (Blade templates)
If you are working on the core package (or an implementation where you are overriding Blade templates) you can set up a file watcher to format Blade templates on save using [blade-formatter](https://github.com/shufo/blade-formatter). Configuration for this is included in the `core` package directory.

In `File > Settings > Tools > File Watchers`:

![Blade file watcher](/phpstorm-blade-filewatcher.png)

**Important:** Ensure you exclude Blade files from PhpStorm's built-in reformatting, as it will cause conflicts. By default, Blade templates will adopt the HTML code style settings, which might not match what you want for Blade due to how it treats @directives as HTML attributes. You can find the setting to exclude Blade files in `File -> Settings -> Editor -> Code Style`. Click Code Style itself at the top level, and then the `Formatter` tab.
:::

:::details JavaScript and TypeScript

[ESLint](https://eslint.org/) is configured for linting and formatting JavaScript and TypeScript code in this project You can enable ESLint to pick up problems within the IDE as well as fix them automatically on save in `File > Settings > Languages & Frameworks > JavaScript > Code Quality Tools > ESLint`.

It _should_ pick up the configuration file located in the root of the project (`./.eslint.config.js`), but you can set the path to your configuration manually if necessary or preferred.

![Eslint on save](/phpstorm-eslint.png)
:::

## PHPUnit

Configuration of PHPUnit is mostly handled in its configuration file (`./test/phpunit.xml`) and the Run configuration.

You can check and tweak the default settings for PhpStorm under `File > Settings > Languages & Frameworks > PHP > Test Frameworks` (shown below) and for the run configuration under `Run > Edit Configurations`.

![PHPUnit settings](/phpstorm-phpunit.png)
