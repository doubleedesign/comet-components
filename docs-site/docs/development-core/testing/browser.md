---
title: Browser testing
position: 2
---

# Browser testing

In order to view the component test out put and pages found in each component's `__tests__` directory for development and manual testing purposes, view them in [Storybook](./storybook.md), and run the [integration tests](./integration-tests.md), you need to run a local web server that can access the test component output files.

[[toc]]

## Option 1: Laravel Herd <Badge text="Recommended" vertical="middle" type="tip" />
[Laravel Herd](https://herd.laravel.com) is a local development environment for PHP applications. It provides a simple way to run a local web server with advanced debugging features.

More details on installing and configuring Herd can be found on the [PHP](../tooling/php.md) page. The below instructions assume you have Herd installed and running.

1. Add Comet Components as a Herd site by either:
   a. opening the Herd app and clicking on the **Add Site** button. Select the project root directory so that the URL will be `http://comet-components.test`.
   b. running `herd link comet-components` from the project root directory in PowerShell.
2. Open the `php.ini` file for the currently active PHP version in Herd. You can find this in `Herd > PHP > right-click on the current version > Open 
php.ini directory`. Add the below lines, filling in your own username and updating the project path as needed:

   ```ini
   herd_auto_prepend_file = C:/Users/YOUR_USERNAME/PHPStormProjects/comet-components/test/browser/wrapper-open.php
   herd_auto_append_file = C:/Users/YOUR_USERNAME/PHPStormProjects/comet-components/test/browser/wrapper-close.php
   ```

3. Restart the PHP service in Herd so that the updated configuration is loaded.

An example of a testing page URL for this setup is:

```
https://comet-components.test/packages/core/src/components/Columns/__tests__/pages/columns-colours.php`.
```

## Option 2: Basic PHP web server

Run the following command in the project root directory to start a basic PHP web server:
	
```bash
php ./test/browser/start.php
```

This will start a PHP web server on port 6001 that runs from the `./test/browser/` directory. You can access the server at `http://localhost:6001`.

An example of a testing page URL for this setup is:

```
http://localhost:6001/packages/core/src/components/Columns/__tests__/pages/columns-colours.php
```

## Option 3: PhpStorm's built-in web server

If you are using PhpStorm, you can also run the built-in web server. This is what you see when you click on the "built in preview" or browser icons that appear in the top right of the editor when you open a file, or right-click on the tab or the file in the Project tool window and navigate to `Open in > Browser`.

For this to work, the `php.ini` configuration file for PhpStorm's currently selected PHP interpreter must have the `auto_prepend_file` and `auto_append_file` values set. For Laravel Herd, follow the instructions above; for other installations, it's the same just without `herd_` at the beginning.

