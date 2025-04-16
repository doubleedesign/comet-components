---
position: 3
---

# CLI command quick reference

[[toc]]

## Convenience scripts

Run these from the root of the project.

| Command                          | Description                                                                                                                                                              |
|----------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `npm run refresh:composer`       | Refresh Composer dependencies and autoloading for the root and all packages  <Badge type="warning" text="Requires PowerShell"/>                                          |
| `npm run refresh:npm`            | Run `npm install` in the root and all packages, and `rollup` in packages that use it  <Badge type="warning" text="Requires PowerShell"/>                                 |
| `npm run refresh:all`            | Install/update Composer and NPM dependencies in the root and all packages <Badge type="warning" text="Requires PowerShell"/>                                             |
| `npm run refresh:autoload`       | Run `composer dump-autoload -o` for the root and all packages (skip updating dependencies)  <Badge type="warning" text="Requires PowerShell"/>                           |
| `npm run refresh:composer:dev`   | Refresh Composer dependencies and autoloading for the root and all packages, using `composer.local.json` if available <Badge type="warning" text="Requires PowerShell"/> |
| `npm run refresh:npmpackages`    | Run `npm install` and `rollup` in the packages only (skips installing/updating root dev deps like Storybook)  <Badge type="warning" text="Requires PowerShell"/>         |
| `php ./scripts/healthcheck.php`  | Check for expected files                                                                                                                                                 |
| `php ./scripts/generate-xml.php` | Regenerate the XML schema for Tycho Template syntax.                                                                                                                     |

### Equivalent standalone commands

These commands need to be run in the relevant folder. Running in the root will only run the command for the root, not the packages. `cd` into the package folder
first if you want to run these for a package.

| Command                                                  | Description                                                                                                                                                                    |
|----------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `composer install`                                       | Install Composer dependencies as per `composer.json`                                                                                                                           |
| `composer update`                                        | Update Composer dependencies as per `composer.json`                                                                                                                            |
| `composer dump-autoload -o`                              | Refresh Composer autoloader after adding new dependencies or classes                                                                                                           |
| `npm install`                                            | Install all NPM dependencies as per `package.json`                                                                                                                             |
| `npm run build`                                          | Run Rollup to compile JS using the package's `rollup.config.js`. For example, for the `core` package this bundles all component JS files and their imports into a single file. |
| `$env:COMPOSER = "composer.local.json"; composer update` | Update Composer dependencies using `composer.local.json` if it exists                                                                                                          |

## Asset compilation

| Command                      | Description                                                                                                          |
|------------------------------|----------------------------------------------------------------------------------------------------------------------|
| `npm run build`              | Bundle Core package component JS and its dependencies into a single file using Rollup                                |
| `sass input.scss:output.css` | Compile a CSS file (or the global file) using Sass (replace `input.scss` and `output.css` with the actual filenames) |

## New component creation

:::warning
The JSON definition and story generators require PHP 8.4+.
:::

Generate a new component (example):

```bash
npm run generate component -- --name=gallery --type=simple
```

Once you have added fields and docblock comments to a component, generate the JSON definition file and [Tycho Template syntax](../usage/overview.md#tycho-template-syntax) XML definition file (example):

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

If adding a "type" enum, regenerate the Markdown documentation page with:

```bash
php scripts/generate-enum-docs.php
```

Similarly, for a trait:

```bash
php scripts/generate-trait-docs.php
```

## Testing

| Command             | Description                                         |
|---------------------|-----------------------------------------------------|
| `npm run storybook` | Run Storybook for component demos and documentation |
