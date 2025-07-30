---
position: 3
---

# Standalone component packages

The core library contains a lot of components, some of which require additional dependencies. There are some components that are useful as standalone packages for minimal use cases or for use in existing projects.

Installation docs for existing standalone packages can be found in [Installation -> Composer](../installation/composer.md) and their individual README files, which are available on GitHub and Packagist.

[[toc]]

## Launchpad package

The packages aren't 100% standalone - they all have one key dependency, the "launchpad" package. Update this package by running:

```powershell
php scripts/update-launchpad-package.php
```

This script:
1. Creates symlinks to essential core classes and the global CSS file in `packages/core-standalone/launchpad`
2. Creates a `PreprocessedHtml` utility class so that consumers can load their own HTML as `innerComponents` rather than needing Comet's headings, paragraphs, etc
3. Generates a `composer.json` file for the package, using the Core package's `composer.json` to get the version, other key metadata, and core dependencies (mainly those used for Blade)
4. Generates a `build-manifest.json` file, which is used by the corresponding GitHub Action to copy the files into the standalone package repo.

## Creating a standalone package

Convenience scripts are provided in the `scripts` directory of the monorepo to create a standalone package for a component:

### 1. Create standalone package

```powershell
php scripts/create-standalone-package.php --component YourComponentName
```

This script:
1. Creates a directory for the package in the `core-standalone` directory
2. Creates symlinks to essential core classes
4. Symlinks the component directory itself (except docs and tests) into the matching location in the standalone package directory (so CSS, JS, Blade templates, and sub-components are all included)
5. Uses [reflection](https://www.php.net/manual/en/book.reflection.php) to find which classes the component extends and traits it uses, and symlinks those too (except those explicitly excluded because they are in the "Launchpad" package)
6. Generates a `composer.json` file for the package, listing the launchpad package as the only dependency by default, using the Core package's `composer.json` to get the version and other key metadata (this may need some adjustment if the component has additional dependencies (such as Ranger for date components)
7. Generates a `build-manifest.json` file, which is used by the corresponding GitHub Action to copy the files into the standalone package repo.

:::info
The reason this uses symlinks instead of copying is to enable use of the standalone packages for local project development using the latest Comet Components including local changes (i.e., symlinking a local project's usage of a standalone Comet package) while ensuring there is no duplication or divergence of code. The resulting `src` directory (full of symlinks) is ignored by Git.
:::

## Automatic publishing and updating

Like the core library and WordPress plugin, the standalone packages can (and should) be configured to be split into their own repos (which are published on Packagist) when a change is pushed to the `master` branch of the monorepo. There are some additional steps, some of which require particular access permissions, so generally require the involvement of the core developer.

Steps a core developer needs to do:
1. Create and initialise a new repository on GitHub for the standalone package, prefixed with `comet-` and the name of the component in kebab-case (this should usually match what's in the generated `composer.json` file)
2. Create a [GitHub personal access token](https://github.com/settings/personal-access-tokens) with appropriate permissions for Actions workflows and access to the monorepo and new repo
3. Add the token as an [actions secret in the monorepo](https://github.com/doubleedesign/comet-components/settings/secrets/actions), called `COMET_TOKEN` (or another name that matches what it's called in the GitHub action)
4. [Submit the repo to Packagist](https://packagist.org/packages/submit) after the first publish makes `composer.json` available in the repo.

Steps anyone can do via pull request:
1. Follow the steps above for creating the package, and commit the changes to the monorepo (this should just be the directory containing `composer.json` and `build-manifest.json`)
2. Add a `REAMDE.md` file with installation and usage instructions, taking care to ensure things like making consumers aware of things like CSS and JS files they need to load in their project for the component to work correctly
3. Create a GitHub Action in the monorepo that runs on `push` to the `master` branch to copy the files using the manifest and push them to the standalone package repo; use [the action for Responsive Panels](https://github.com/doubleedesign/comet-components/blob/master/.github/workflows/split-core-standalone-responsive-panels.yml) as a guide.
