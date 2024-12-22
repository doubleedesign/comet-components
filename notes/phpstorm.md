# My PHPStorm setup

> [!TIP]
> Ensure you have [PHP](./php.md) and [Node](./node.md) set up locally before continuing.

1. [PHP interpreter](#php-interpreter)
2. [Xdebug](#xdebug)
3. [PHPUnit](#phpunit)
4. [Node](#node)
5. [Linting and formatting](#linting-and-formatting)
    - [PHP - PHPStorm code style settings](#php---phpstorm-formatting)
    - [JavaScript and TypeScript - ESLint](#javascript-and-typescript---eslint)

---

## PHP interpreter

For IDE tools such as the PHPUnit test runner and Xdebug integration to work, you will need to set the PHP interpreter
and language version. For consistency, usually this should be the same as the PHP instance you are using in your
terminal.

You can find these settings in PHPStorm under `File > Settings > Languages & Frameworks > PHP`. If your PHP instance is
not listed, click the 3 dots next to the dropdown and add it.

Below is an example of adding Laravel Herd's PHP 8.4 instance:

![PHP interpreter settings](images/phpstorm-php.png)

Generally, it should pick up the configuration file (`php.ini`) automatically, but if you have some custom setup or
problems with that you can set the path to your preferred file in the field provided in this dialog.

---

## Xdebug

If you have Xdebug installed and enabled in your PHP instance, PHPStorm will use it for debugging and code coverage. You
can check if it is available in the interpreter settings as per the above.

---

## PHPUnit

Configuration of PHPUnit is mostly handled in its configuration file (`./test/phpunit.xml`). You can check and tweak the
settings for PHPStorm under `File > Settings > Languages & Frameworks > PHP > Test Frameworks`.

![PHPUnit settings](images/phpstorm-phpunit.png)

---

## Node

Ensure that PHPStorm's default Node interpreter matches the one you are using in your terminal. If this is not set
correctly, tools like ESLint won't work in the editor and you may experience inconsistent or unexpected behaviour when
switching between doing tasks in the terminal and the IDE.

You can find the setting for this in Settings > Languages & Frameworks > Node.js.

![Node settings](images/phpstorm-node.png)

---

## Linting and formatting

### PHP - PHPStorm formatting

I tend to use PHPStorm's built in formatting options for PHP (mainly because I used them long before I started working
with
JavaScript regularly and had no need or desire to use a separate formatting tool)[^1]. You can find these settings in
`File > Settings > Editor > Code Style > PHP`.

You can enable enforcing formatting rules on save in `File > Settings > Tools > Actions on save` by ticking the "
Reformat code" option.

> [!IMPORTANT]
> Ensure JavaScript and TypeScript are not ticked in the list of file types to reformat on save, to avoid conflicts with
> ESLint.

![Actions on save.png](images/phpstorm-save.png)

I include my PHPStorm project config files in this repository for my own convenience, so the same formatting settings
should be available (as the "Project" settings) to you if you are using PHPStorm.

[^1]: There is a [Prettier plugin for PHP](https://github.com/prettier/plugin-php) that I have used for
particular purposes, but I have not included it in this project at the time of writing for a few reasons:

- PHPStorm's options meet my needs so why add another dependency
- I don't want to use Prettier for JavaScript - I prefer ESLint's options and don't want to double up on formatting
  tools
- It doesn't cover all the formatting options I use in PHPStorm, so I'd end up using both which could cause conflicts.

### JavaScript and TypeScript - ESLint

I use [ESLint](https://eslint.org/) for linting and formatting JavaScript and TypeScript code. You can enable ESLint to
pick up problems within the IDE as well as fix them automatically on save in
`File > Settings > Languages & Frameworks > JavaScript > Code Quality Tools > ESLint`.

It _should_ pick up the configuration file located in the root of the project (`./.eslint.config.js`), but you can set
the path to your configuration manually if necessary or preferred.

![Eslint on save](images/phpstorm-eslint.png)

---
