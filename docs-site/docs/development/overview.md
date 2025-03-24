# Overview

[[toc]]

## Tech Stack

### Core Technologies

| Technology | Description                    | Details/Rationale                                                                                                                                                                                                                                                                                                 |
|------------|--------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| PHP        | Primary programming language   | I like it. And because I'm using this in WordPress.                                                                                                                                                                                                                                                               |
| Blade      | Templating engine for PHP      | I wanted to try using a templating language, and a) preferred the syntax over Twig and b) have vague notions of working with Laravel at some point in the future.                                                                                                                                                 |
| JavaScript | Secondary programming language | Used in the WordPress plugin where necessary in the back-end for custom blocks or customisations of core blocks (anything that can't be done in PHP), and in the standalone library for client-side interactivity.                                                                                                | |
| Vue.js     | Front-end JavaScript framework | Used in select places to provide more advanced interactivity and responsiveness, such as the responsive menu in the `SiteHeader`. [Vue SFC Loader](https://github.com/FranckFreiburger/vue3-sfc-loader) is used to Vue-ify certain components (or parts of them) without the whole thing needing to be a Vue app. |

### Third-party plugins

| Plugin      | Description       | Details/Rationale                                                                                                                         |
|-------------|-------------------|-------------------------------------------------------------------------------------------------------------------------------------------|
| Bootstrap   | Front-end toolkit | Some of Bootstrap's JavaScript plugins are used to provide basic client-side interactivity for components such as `Accordion` and `Tabs`. |
| BaguetteBox | Vanilla JS plugin | Used to enable lightbox functionality in the `Gallery` component.                                                                         |

## Dev Tooling

| Technology | Description                | Details/Rationale                                                                                                                                                                                                                                                                                                    |
|------------|----------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| SCSS       | CSS preprocessor           | Intended to not be essential for consuming projects, but used in Comet Core for improved developer experience for the library's foundational CSS.                                                                                                                                                                    |
| Rollup     | JavaScript bundler         | Used to bundle the core package's JavaScript into one file, to make it easier for implementations to use. (I previously had import path issues when trying to use the individual scripts in the WP plugin for example - this solves those.)                                                                          |
| Composer   | PHP package manager        | Used to manage PHP dependencies, and within packages other dependencies that should be uploaded to the server (e.g., Bootstrap plugins). Dev-only dependencies should be installed at the project root, so that packages' `vendor` folders contain only production dependencies.                                     |
| NPM        | JavaScript package manager | Used to manage JavaScript dependencies and build scripts. Primarily for local development and tooling (e.g. Storybook, Rollup). `node_modules` for any package should not be uploaded to the server - any JavaScript dependencies that reside here need to be compiled into production bundles that do get uploaded. |

## Directory structure

:::warning
// TODO more detail here
:::

## General quick tips

- When developing for the WordPress plugin, running with Xdebug on can slow things down. If loading the editor or saving
  seems unduly slow, test with Xdebug off to confirm if it's just that or if you have an actual performance issue.

## Glossary of terms

| Term                      | Definition                                                                                                                                                                                                                                               |
|---------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| WSL                       | Windows Subsystem for Linux. This allows you to run a Linux terminal within Windows, which provides a Bash shell as opposed to the Command Prompt or PowerShell.                                                                                         |
| NVM                       | Node Version Manager. This is a tool that allows you to manage multiple versions of Node.js on your machine.                                                                                                                                             |
| NPM                       | Node Package Manager. This is a tool that comes with Node and allows you to install and manage JavaScript packages. For context, it's like Composer for PHP. The main place to find packages you can use with it on [npmjs.com](https://www.npmjs.com/). |
| Composer                  | A package/dependency manager for PHP. It's like NPM for PHP, with additional features for things like class loading. The main place to find packages you can use with it is [Packagist](https://packagist.org/).                                         |
| Chocolatey, Homebrew, APT | OS-level package managers that enable you to install and manage things like PHP and Node from the command line. [Chocolatey](https://community.chocolatey.org/) is for Windows, [Homebrew](https://brew.sh/) is for MacOS, and APT is built into Linux.  |
