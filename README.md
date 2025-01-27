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
2. Run `npm run refresh` or `powershell.exe -File scripts\refresh.ps1` to install/update PHP dependencies and regenerate
   autoload files for the root and all packages
3. Run `npm install` to install Node dependencies
4. Run `npm run test:server` and `npm run test:storybook` at the same time (I use two terminal windows) to start the
   local web server and Storybook to see what you're working with!

### General quick tips

- When developing for the WordPress plugin, running with Xdebug on can slow things down. If loading the editor or saving
  seems unduly slow, test with Xdebug off to confirm if it's just that or if you have an actual performance issue.

### Create a new component

To generate the boilerplate code for a new component, run the following command with `example` and `simple` replaced
with the desired component name and type. Valid types are `simple`, `complex`, and `wrapper`.

```bash
npm run generate component -- --name=example --type=simple
```

// TODO More to come here, especially re CSS and JS files for each component.

You will need to add the SCSS file to `blocks.scss` in the WordPress plugin and run Sass to compile it. (Historically, I
always used Gulp with a plugin to import all component SCSS using a glob pattern, but because `@import` is being
deprecated in Sass that approach's days are numbered.)

### Automated tests

See [testing notes](./notes/testing.md) for more information.

### Appendix 1: CLI command quick reference

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
npm run refresh
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

Use the same command to update it after making changes.

You can also generate/update all JSON definition files at once:

```bash
php scripts/generate-json-defs.php 
```

or

```bash
npm run generate:json
```

Then generate stories:

```bash
# TO COME
````

---

### Appendix 2: Troubleshooting

See [troubleshooting notes](./notes/troubleshooting.md).

### Appendix 3: From-scratch environment setup on Windows

See [Windows setup notes](./notes/windows.md).

---

## Acknowledgements

- Thank you to [Jozo](https://github.com/jozo) for
  the [JSON of HTML elements and their attributes](https://github.com/jozo/all-html-elements-and-attributes/blob/master/html-elements.json)
  used for generating the `Tag` enum.
