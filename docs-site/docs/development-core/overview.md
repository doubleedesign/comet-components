---
title: Overview
position: 0
---

# Core library development overview

:::info
This section of the docs covers developing the Comet Components core library itself.

Developers looking to use the library in their projects should refer to the [Usage](../usage/overview.md) and [New Implementations](../development-new/overview.md) sections.
:::

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
| BaguetteBox | Vanilla JS plugin | Used to enable lightbox functionality in the `Gallery` component.                                                                         |

## Dev Tooling

| Technology | Description                | Details/Rationale                                                                                                                                                                                                                                                                                                   |
|------------|----------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| SCSS       | CSS preprocessor           | Intended to not be essential for consuming projects, but used in Comet Core for improved developer experience for the library's foundational CSS.                                                                                                                                                                   |
| Rollup     | JavaScript bundler         | Used to bundle the core package's JavaScript into one file, to make it easier for implementations to use. (I previously had import path issues when trying to use the individual scripts in the WP plugin for example - this solves those.)                                                                         |
| Composer   | PHP package manager        | Used to manage PHP dependencies, and within packages other dependencies that should be uploaded to the server. Dev-only dependencies should be installed at the project root, so that packages' `vendor` folders contain only production dependencies.                                     |
| NPM        | JavaScript package manager | Used to manage JavaScript dependencies and build scripts. Primarily for local development and tooling (e.g. Storybook, Rollup). `node_modules` for any package should not be uploaded to the server - any JavaScript dependencies that reside here need to be compiled into production bundles that do get uploaded. |

## Directory structure

| Directory     | Subdirectory      | Purpose                                                                                                                                                                                   |
|---------------|-------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `./docs`      |                   | The built documentation site, which is generated from the `docs-site` directory. **Do not edit the contents of this folder - it will get overwritten the next time the site is built**.   |
| `./docs-site` |                   | The source files for this documentation site, which is built with [VuePress](https://vuepress.vuejs.org/).                                                                                |
| `./packages`  | `/core`           | The core package, which contains the library's components and styles.                                                                                                                     |
| `./packages`  | `/comet-plugin`   | The core WordPress plugin, which implements Comet versions of select WordPress core blocks and most other core Comet Components.                                                          |
| `./packages`  | `/comet-calendar` | The WordPress plugin for managing and displaying event information using Comet Components.                                                                                                |
| `./packages`  | `/comet-canvas`   | WordPress parent theme. Implements Comet Components for global layout elements such as the header and footer, and provides theming foundations and hooks for child theme implementations. |
| `./scripts`   |                   | Utilities for local development.                                                                                                                                                          |
| `./test`      |                   | Configuration and utilities for the browser testing environment, unit testing, and integration testing.                                                                                   |

## Glossary of terms

| Term                      | Definition                                                                                                                                                                                                                                               |
|---------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| WSL                       | Windows Subsystem for Linux. This allows you to run a Linux terminal within Windows, which provides a Bash shell as opposed to the Command Prompt or PowerShell.                                                                                         |
| NVM                       | Node Version Manager. This is a tool that allows you to manage multiple versions of Node.js on your machine.                                                                                                                                             |
| NPM                       | Node Package Manager. This is a tool that comes with Node and allows you to install and manage JavaScript packages. For context, it's like Composer for PHP. The main place to find packages you can use with it on [npmjs.com](https://www.npmjs.com/). |
| Composer                  | A package/dependency manager for PHP. It's like NPM for PHP, with additional features for things like class loading. The main place to find packages you can use with it is [Packagist](https://packagist.org/).                                         |
| Chocolatey, Homebrew, APT | OS-level package managers that enable you to install and manage things like PHP and Node from the command line. [Chocolatey](https://community.chocolatey.org/) is for Windows, [Homebrew](https://brew.sh/) is for MacOS, and APT is built into Linux.  |
