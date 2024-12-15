# Comet Components

An early work-in-progress, experimental, to-become-cross-platform web UI component library. Initially being developed as an abstraction layer for WordPress blocks, with the intention of being able to use the same components in other projects.

## Usage

### In WordPress

TO COME: WordPress plugin with my core block customisations, common custom blocks, etc.

### As a standalone PHP library

TO COME.

---
## Development

> [!NOTE]
> I use Windows for developing my projects locally and generally like to use [WSL](https://learn.microsoft.com/en-us/windows/wsl/) (Debian on WSL1) as my terminal, with PowerShell as my second choice (and where I do anything Windows-specific that I can't easily do in my WSL setup). All notes/docs/config/scripts throughout this project reflect this.

### Prerequisites

- PHP and [Composer](https://getcomposer.org) installed locally 
- [Node](https://nodejs.org) installed locally
- Git installed locally
- IDE of choice (I use [PHPStorm](https://www.jetbrains.com/phpstorm/))

Windows users can find more details on [PHP, Composer, and Node setup below](#appendix-1-from-scratch-environment-setup-on-windows). 

### Quick start

1. Clone the repository from GitHub
2. Run `composer install` to install PHP dependencies
3. Run `npm install` to install Node dependencies
4. TO COME: Run Storybook to see what you're working with

### Testing

See [testing notes](./notes/testing.md) for more information.

---
### Appendix 1: From-scratch environment setup on Windows

As a first step, if you prefer the command line I'd recommend installing [Chocolatey](https://community.chocolatey.org/) if you haven't already. If you prefer to do things using a GUI, you might like to use [Laravel Herd](https://herd.laravel.com/windows). If you don't have existing instances of PHP, Composer, or Node installed, the latter basically gets you up and running out-of-the-box.

The below-linked notes detail how I set up my local development environment, along with some other options I have tested. It is not essential to follow these steps or to use the same setup as me, but they may be helpful if you are new to any of the tools or technologies listed here or are new to using Windows for web development.

These notes cover both doing everything natively (using PowerShell as your terminal) and using WSL (for a Bash terminal), and also include IDE configuration information for PHPStorm.

- [PHP development setup on Windows](./notes/php.md)
- [Node development setup on Windows](./notes/node.md)
- [My WSL setup](./notes/wsl.md)

#### Additional background information
- [Glossary of terms](./notes/glossary.md)
- [About Windows system PATH](./notes/path.md)

---
### Appendix 2: Troubleshooting
- [Miscellaneous troubleshooting notes](./notes/troubleshooting.md)

---
