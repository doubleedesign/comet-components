---
title: Unit testing (Pest)
position: 1
---

# Unit testing

[[toc]]

## About the setup

The unit tests are set up to use [Pest](https://pestphp.com/), with the following utility add-on libraries:
- [Patchwork](https://patchwork2.org/) for convenient mocking functions that support class methods 
- [Phluent](https://github.com/Haberkamp/phluent) for convenient plain-English assertion syntax.

Pest uses [PHPUnit](https://phpunit.de/index.html) under the hood, but provides a syntax similar to JavaScript test runners such as Jest and Playwright.

Some custom utility functions are included in the `PestUtils` class, which can be found in `./test/unit/PestUtils.php` and used in tests by importing the class at the top of your test file:

```php
use Doubleedesign\Comet\TestUtils\PestUtils;
```

:::important
If adding additional utility libraries for unit testing, they should go in the dev dependencies in the project root's `composer.json` file. All testing configuration should go in the root in the first instance, so it can be shared amongst packages while also helping to keep them lean for distribution.

If adding any testing utilities of your own that are relevant only to a specific package, ensure they are excluded from distribution in that package's `composer.json` under `archive > exclude`. See the `core` package's `composer.json` for an example.
:::

## Writing tests

### Naming conventions
- Test classes and files should be in PascalCase with the word `Test` appended to the class name. e.g., tests for `Container` are in `ContainerTest.php`.

### Boilerplate for a new test file

```php
<?php
use Doubleedesign\Comet\Core;

test('some test case description', function() {
    ob_start();
    $component = new YourComponent([], []);
    $component->render();
    $output = ob_get_clean();

    $dom = new DOMDocument();
    @$dom->loadHTML($output);
    $component = $dom->getElementsByTagName('div')->item(0);

    // assertions
});
```

### Identifying DOM elements using DOMDocument

You can render a component within a test, capturing its HTML using [output buffering](https://www.php.net/manual/en/outcontrol.output-buffering.php):

```php
ob_start();
$component = new YourComponent([], []);
$component->render();
$output = ob_get_clean();
```

Then, you can use the [DOMDocument](https://www.php.net/manual/en/class.domdocument.php) class to parse the HTML and find elements. For example:

```php
$dom = new DOMDocument();
@$dom->loadHTML($output);
$wrapper = $dom->getElementsByTagName('div')->item(0);
```

In this example, `$wrapper` will be a [DOMElement](https://www.php.net/manual/en/class.domelement.php) object representing the first `<div>` in the rendered HTML.

:::warning
`$dom->getElementsByTagName()` ignores hierarchy. This means that, for example, if you want to get the first two inner divs of a component but they have their own inner divs, `dom->getElementsByTagName('div')->item(1)` will give you the first child's own first child, not the second child of your component.

A utility function, `PestUtils::getElementsByClassName()`, is available to get elements by their class name instead of just their tag name.
:::


## Running tests

To run a test file from the terminal:
:::warning
// TODO: Updated instructions to come 
:::

To run all tests in a file in PhpStorm:
- Right-click the test file's tab in the editor or its name in the Project tool window and select `Run FileName.test (Pest)`. This will use the included Run configuration template unless you configure a custom one.

To run a single test from the gutter icon in PhpStorm:
- :warning: // TODO: This is not currently supported because the automatic filtering of the test name is not working. Run the whole file instead as a workaround.

To run all unit tests in PhpStorm:
- A Run configuration has been included for running all tests.

## Coverage reporting

You will need [Xdebug](https://xdebug.org/) installed and enabled to generate coverage data and reports. See the [PHP setup notes](../tooling/php.md) for more information.

A PhpStorm Run Configuration has been included in this repository for running all tests in the core package and generating a HTML coverage report. To see coverage data using the IDE coverage tools, use the "Run with coverage" option. By default, this is located in the top right corner of the PhpStorm window (shown below); you can also find it in the Run menu.

![Run with coverage screenshot](/phpstorm-run-with-coverage.png)]

## Troubleshooting


:::details Logging to the console

`print_r` and `var_dump` may not work in unit tests. Instead, you can use:

```php
fwrite(STDERR, 'something to output as an error');
```
```php
fwrite(STDOUT, 'something to output as a basic message');
```

Even better, if you are using Laravel Herd you can send output to the Herd Dumps window which is much more powerful, allowing you to directly log entire objects and arrays:

```php
\Symfony\Component\VarDumper\VarDumper::dump($variable);
```
:::

::: details PhpStorm not detecting Xdebug when using Laravel Herd
If you are trying to use Xdebug for unit test code coverage and nothing has triggered Herd's automatic Xdebug detection, you can enable it manually:

1. Ensure the PHP version the default interpreter in PhpStorm (found in `Settings > PHP`)is set to is the same as the one currently set as the global version in Herd. In the Herd dashboard, under `Active Services` it should also say that version is active with debugging - e.g., `PHP 8.4 (debug)`
2. Back in PhpStorm > `Settings > PHP > CLI Interpreter`, click the 3 dot button to go into the interpreter details and click the `Reload PHPInfo` button
3. If it's still not picking up Xdebug, try putting the path to the extension in the `Additional` section. This will be something like `C:\Users\YOUR_USERNAME\.config\herd\bin\xdebug\xdebug-8.4.dll`. Click the reload button again, and it should pick it up if you have the file path correct.
4. In `Settings > PHP > PHP Runtime tab` and click "sync extensions with interpreter".

Below is an example of the CLI interpreter settings using this method:

![PhpStorm CLI interpreter settings with Xdebug manually enabled](/phpstorm-xdebug-fix.png)

Try running the unit tests with coverage again. If using the PhpStorm run configuration for Pest, select "Run [test config] with coverage" as shown below:

![Pest run with coverage](/phpstorm-run-with-coverage.png)

If it works, the `Coverage` tool window should populate when the tests have finished (and coverage data should be shown in the Project tool window and in the files themselves until you close the active suite in the Coverage tool window).

Below is an example of the test run output and the coverage tool window.

[![PhpStorm test run output and coverage](/phpstorm-coverage-window.png)

:::
