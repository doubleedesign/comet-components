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

Convenience scripts are provided in the `scripts` directory of the monorepo to create a standalone package for a component like so:

```powershell
php scripts/create-standalone-package.php --component YourComponentName
```

This script:
1. Creates a directory for the package in the `core-standalone` directory
2. Creates symlinks to essential core classes
4. Symlinks the component directory itself (except docs and tests) into the matching location in the standalone package directory (so CSS, JS, Blade templates, and sub-components are all included)
5. Uses [reflection](https://www.php.net/manual/en/book.reflection.php) to find which classes the component extends and traits it uses, and symlinks those too (except those explicitly excluded because they are in the "Launchpad" package)
6. Generates a `composer.json` file for the package, listing the launchpad package as the only dependency by default, using the Core package's `composer.json` to get the version and other key metadata (this may need some adjustment if the component has additional dependencies (such as Ranger for date components)
7. Generates a `build-manifest.json` file, which is used by the corresponding GitHub Action to copy the files into the standalone package repo. This step uses a different script, so if you have made manual changes to the package you can use the following command to regenerate the manifest:

```powershell
php scripts/generate-standalone-manifest.php --component YourComponentName
```

:::info
The reason this uses symlinks instead of copying is to enable use of the standalone packages for local project development using the latest Comet Components including local changes (i.e., symlinking a local project's usage of a standalone Comet package) while ensuring there is no duplication or divergence of code. The resulting `src` directory (full of symlinks) is ignored by Git.
:::

## Updating a standalone package

To update an existing standalone package, you can use the same script as above - essentially regenerating the package.

```powershell
php scripts/create-standalone-package.php --component YourComponentName
```

## Automatic publishing and updating

Like the core library and WordPress plugin, the standalone packages can (and should) be configured to be split into their own repos (which are published on Packagist) when a change is pushed to the `master` branch of the monorepo. This is done via GitHub actions, so there are some additional steps (some of which require particular access permissions, so generally require the involvement of the core developer).

:::warning
The standalone packages don't yet update automatically when the Core library itself changes. So when making changes to the relevant components, please remember to:
1. Do a minor version bump in the Core library's `composer.json` file (as this is used for versioning the standalone packages)
2. Run the script to ensure the Launchpad package is up to date
3. Run the script to create or update the standaloAdding a new standalone packagene package(s) for anything you've modified
4. Commit the changes to the monorepo.

:::

:::details Adding a new standalone package
Steps a core developer needs to do for a new standalone package:
1. Create and initialise a new repository on GitHub for the standalone package, prefixed with `comet-` and the name of the component in kebab-case (this should usually match what's in the generated `composer.json` file)
2. Create a [GitHub personal access token](https://github.com/settings/personal-access-tokens) with appropriate permissions for Actions workflows and access to the monorepo and new repo
3. Add the token as an [actions secret in the monorepo](https://github.com/doubleedesign/comet-components/settings/secrets/actions), called `COMET_TOKEN` (or another name that matches what it's called in the GitHub action)
4. [Submit the repo to Packagist](https://packagist.org/packages/submit) after the first publish makes `composer.json` available in the repo.

Steps anyone can do via pull request for a new standalone package:
1. Follow the steps above for creating the package, and commit the changes to the monorepo (this should just be the directory containing `composer.json`, `build-manifest.json`, and `README.md`)
2. Add a `REAMDE.md` file with installation and usage instructions, taking care to ensure things like making consumers aware of things like CSS and JS files they need to load in their project for the component to work correctly.
   :::

:::details Updating an existing standalone package
Anyone can also update an existing standalone package by following the steps above to update the launchpad and standalone package in question, and submitting a pull request to the monorepo. When finalised and merged, the GitHub Action will then run automatically to update the standalone package repo.
:::
