# Comet Components

An early work-in-progress, experimental, to-become-cross-platform web UI component library. Initially being developed as
an abstraction layer for WordPress blocks, with the intention of being able to use the same components in other
projects.

## Usage

### In WordPress

// TODO WordPress plugin with my core block customisations, common custom blocks, etc.

### As a standalone PHP library

// TODO.

---

## Development

1. [Prerequisites](#prerequisites)
2. [Quick start](#quick-start)
3. [General quick tips](#general-quick-tips)
4. [Create a new component](#create-a-new-component)
5. [Automated tests](#automated-tests)

- [Appendix 1: CLI command quick reference](#appendix-1-cli-command-quick-reference)
- [Appendix 2: Troubleshooting](./notes/troubleshooting.md)
- [Appendix 3: From-scratch environment setup on Windows](./notes/windows.md)

> [!NOTE]
> I use Windows for developing my projects locally and generally like to
> use [WSL](https://learn.microsoft.com/en-us/windows/wsl/) (Debian on WSL1) as my terminal, with PowerShell as my
> second
> choice (and where I do anything Windows-specific that I can't easily do in my WSL setup). All
> notes/docs/config/scripts
> throughout this project reflect this.

### Prerequisites

- PHP and [Composer](https://getcomposer.org) installed locally
- [Node](https://nodejs.org) installed locally
- Git installed locally
- [Sass](https://sass-lang.com) installed globally on your machine
- IDE of choice (I use [PHPStorm](https://www.jetbrains.com/phpstorm/))

Windows users can find more details
on [PHP, Composer, Node, and Sass setup in this document](./notes/windows.md).

### Quick start

1. Clone the repository from GitHub
2. Refresh dependencies, autoloading, and symlinks: (**Note:** These run PowerShell scripts under the hood)
    ```bash
    # Composer dependencies and autoloading
    npm run refresh:composer
    ```
    ```bash
    # Symlink component assets (e.g., CSS) to the browser testing assets directory
    npm run refresh:symlinks
    ```
    ```bash
    # Both
    npm run refresh
    ```
3. Install Node dependencies
    ```bash
    npm install
    ```
4. Run the local web server and Storybook (at the same time i.e. two terminal windows) to see what you're working with!
    ```bash
    npm run test:server
    ```
    ```bash
    npm run test:storybook
    ```

### General quick tips

- When developing for the WordPress plugin, running with Xdebug on can slow things down. If loading the editor or saving
  seems unduly slow, test with Xdebug off to confirm if it's just that or if you have an actual performance issue.

### Create a new component

1. To generate the boilerplate code for a new component, run the following command with `example` and `simple` replaced
   with the desired component name and type. Valid types are `simple`, `complex`, and `wrapper`.

    ```bash
    npm run generate component -- --name=example --type=simple
    ```

2. Add the SCSS file to `blocks.scss` in the WordPress plugin and [run Sass](./notes/sass.md) to compile it.[^1]
3. Update the symlinks so the local web server/Storybook can access the CSS file.
    ```bash
    npm run refresh:symlinks
    ```
4. Add a file to `./test/browser/components` with a sample usage of the component. Add handling for `$_GET` parameters
   to allow for different attributes to be tested.
5. Create a Storybook file in `./test/browser/stories`. // TODO - I plan on adding boilerplate generation of this too.

### Check for expected files

```bash
php ./scripts/healthcheck.php
```

[^1]: Historically, I
always used Gulp with a plugin to import all component SCSS using a glob pattern, but because `@import` is being
deprecated in Sass that approach's days are numbered.

### Automated tests

See [testing notes](./notes/testing.md) for more information.

### Appendix 1: CLI command quick reference

Note: Composer commands need to be run in each relevant package folder that has a `composer.json` file.

Refresh Composer autoloader after adding new classes:

```bash
composer dump-autoload -o
```

Install Composer dependencies (after a fresh clone of the project):

```bash
composer install
```

Update Composer dependencies (after a pull from the repository or other changes to `composer.json`):

```bash
composer update
```

Run all Composer update and autoload commands for the root and packages at once:

```bash
npm run refresh:composer
```

```bash
powershell.exe -File scripts\refresh.ps1
```

or in PowerShell:

```PowerShell
powershell.exe -File scripts\refresh.ps1
```

Install or update Node dependencies:

```bash
npm install
```

Run basic local web server to view/work with isolated component demos:

```bash
npm run test:server
```

Run Storybook for a more robust and detailed browser demo/testing environment (note: server needs to be running):

```bash
npm run test:storybook
```

Generate a new component (example):

```bash
npm run generate component -- --name=gallery --type=simple
```

Once you have added fields and docblock comments to a component, generate the JSON definition file (example):

```bash
php scripts/generate-json-defs.php --component Gallery
```

Or for a new base/abstract/parent component:

```bash
php scripts/generate-json-defs.php --base Renderable
```

Use the same command to update these after making changes.

You can also generate/update all JSON definition files at once:

```bash
php scripts/generate-json-defs.php 
```

Then generate stories (example):

```bash
php scripts/generate-stories.php --component Container
```

If adding a "type" enum, generate an MDX documentation page for Storybook with:

```bash
php scripts/generate-enum-docs.php --enum Alignment
```

**Note:** The JSON definition and story generators require PHP 8.4+.

---

### Appendix 2: Troubleshooting

See [troubleshooting notes](./notes/troubleshooting.md).

### Appendix 3: From-scratch environment setup on Windows

See [Windows setup notes](./notes/windows.md).

---

## With thanks to

- [TypeScale](https://type-scale.com) 
- [Jozo](https://github.com/jozo) for
  the [JSON of HTML elements and their attributes](https://github.com/jozo/all-html-elements-and-attributes/blob/master/html-elements.json)
  used for generating the `Tag` enum.
