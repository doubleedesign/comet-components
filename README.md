# Comet Components

A PHP-driven web UI component library. Initially being developed as
an abstraction layer for WordPress blocks, with the intention of being able to use the same components in other
projects.

---
## Local dev quick start guide

### Prerequisites
- PHP and [Composer](https://getcomposer.org) installed locally
- [Node](https://nodejs.org) installed locally
- Git installed locally
- [Sass](https://sass-lang.com) installed globally on your machine
- IDE of choice (I use [PhpStorm](https://www.jetbrains.com/phpstorm/))

### Setup

After cloning the repo, with admin privileges* run:

```bash
npm run refresh:all
```

This will ensure everything is installed and up-to-date for development. It will:
- run all Composer and NPM commands in the root, packages, and documentation site directories
- create symlinks in the relevant places*

*The provided script for symlinks uses PowerShell, and creating symlinks this way requires admin privileges. If you're on a Mac or Linux, or Windows without being able to run PowerShell with elevated privileges, you'll need to create the symlinks another way.

### Documentation site

General and developer documentation is in a [VuePress](https://vuepress.vuejs.org) site, which you can run locally with:

```bash
npm run docs
```

### Storybook

Detailed component documentation and demos are in Storybook.

In separate terminal windows, run:

```bash
npm run test:server
```
```bash
npm run test:storybook
```
---
## With thanks to

- [TypeScale](https://type-scale.com)
- [Jozo](https://github.com/jozo) for the [JSON of HTML elements and their attributes](https://github.com/jozo/all-html-elements-and-attributes/blob/master/html-elements.json) used for generating the `Tag` enum
- Franck Freiburger and contributors for the brilliant [Vue SFC Loader](https://github.com/FranckFreiburger/vue3-sfc-loader) that allows me to sprinkle Vue into my PHP (particularly WordPress) projects
- The presenters and attendees at WordCamp Sydney 2024 for the conversations around the earlier work I presented there that led to this project, and the ideas they presented that informed the approach I've taken here
- ...and the core developers contributors behind every other library and tool used in this project!
