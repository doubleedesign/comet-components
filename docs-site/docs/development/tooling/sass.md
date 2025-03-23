---
title: Sass
---

# Sass (SCSS) development setup

[SCSS](https://sass-lang.com/) or SASS (Syntactically Awesome Style Sheets, written as Sass in the official docs despite being an acronym) is a CSS
preprocessor that
allows the
definition and use of variables,
functions, and
mixins to make CSS more modular and maintainable.

- [Installing and running SASS](#installing-and-running-sass)
- [Development approach](#development-approach)

---

## Installing and running SASS

Assuming you have already set up [Node](./node.md), you can install SASS globally using NPM:

::: tabs#shell
@tab Bash
```bash:no-line-numbers
npm install -g sass
```
@tab PowerShell
```powershell:no-line-numbers
npm install -g sass
```
:::

:::tip

The JavaScript implementation the NPM package uses is supposedly slower than Dart Sass on is own. Windows users can use Chocolatey to
install Dart Sass globally:

::: tabs#shell
@tab PowerShell
```powershell:no-line-numbers
choco install sass
```
:::

:::details Caveats for Chocolatey + WSL

Using the Chocolatey version of can cause some permission and file path headaches when going via WSL and trying to use glob patterns or
the watcher though (even with an alias in your Bash config).

If using PhpStorm, using a [file watcher](./phpstorm.md) gets around this.
:::

To compile a single file and watch for changes (example):

::: tabs#shell
@tab Bash
```bash:no-line-numbers
sass global.scss:global.css --watch
```
@tab PowerShell
```powershell:no-line-numbers
sass global.scss:global.css --watch
```
:::

To watch all files in a directory using PhpStorm, you can use a [file watcher](./phpstorm.md).

---

## Development Approach

Please see the [HTML, CSS and SCSS](../technical-deep-dives/html-css-sass.md) page for more information on the approach to using SCSS in this project.

