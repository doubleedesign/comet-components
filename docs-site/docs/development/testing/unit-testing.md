---
title: Unit testing (PHPUnit)
---

# Unit testing

[[toc]]

## About the setup

The unit tests are set up to use [PHPUnit](https://phpunit.de/index.html) with the following utility add-on libraries:
- [Patchwork](https://patchwork2.org/) for convenient mocking functions that support class methods 
- [Phluent](https://github.com/Haberkamp/phluent) for convenient plain-English assertion syntax.

:::important
If adding additional utility libraries for unit testing, they should go in the dev dependencies in the project root's `composer.json` file. All testing configuration should go in the root in the first instance, so it can be shared amongst packages while also helping to keep them lean for distribution.

If adding any testing utilities of your own that are relevant only to a specific package, ensure they are excluded from distribution in that package's `composer.json` under `archive > exclude`. See the `core` package's `composer.json` for an example.
:::

## Writing tests

### General things to note about PHPUnit

:::details Naming conventions
- Test classes and files should be in PascalCase with the word `Test` appended to the class name. e.g., tests for `Container` are in `ContainerTest.php`.
- Tests are functions within the class, and need to either start with `test_` or be marked with the `#[Test]` attribute.

:::

### Boilerplate for a new test file

```php
<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\Core;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use DOMDocument;

#[TestDox("YourComponent")]
class YourComponentTest extends TestCase {

	#[TestDox('It does something')]
	#[Test] public function something_happens() {
		ob_start();
		$component = new YourComponent([], []);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$element = $dom->getElementsByTagName('div')->item(0); // update this to find your component

		// Assertions
	}
}

```

## Running tests

To run a test file from the terminal:

::: tabs#shell
@tab WSL (Bash)
```bash:no-line-numbers
./vendor/bin/phpunit packages/core/src/components/Container/__tests__/ContainerTest.php --configuration ./test/phpunit.xml
```
@tab PowerShell
```powershell:no-line-numbers
./vendor/bin/phpunit packages/core/src/components/Container/__tests__/ContainerTest.php --configuration ./test/phpunit.xml
```
:::

PhpStorm users can also run selected tests or files from the gutter icon. A PHPUnit run config template has been included which will be used for that unless you configure otherwise. Similarly, a Run configuration has been included for running all tests.

## Coverage reporting

You will need [Xdebug](https://xdebug.org/) installed and enabled to generate coverage data and reports. See the [PHP setup notes](../tooling/php.md) for more information.

A PhpStorm Run Configuration has been included in this repository for running all tests in the core package and generating a HTML coverage report. To see coverage data using the IDE coverage tools, use the "Run with coverage" option. By default, this is located in the top right corner of the PhpStorm window (shown below); you can also find it in the Run menu.

![Run with coverage screenshot](/phpstorm-run-with-coverage.png)]

## Troubleshooting

:::details General debugging

To debug unexpected test failures (or silent failures - where the test passes but you know it shouldn't, the number of assertions is wrong, etc), you can run PHPUnit with the `--debug` flag. For example:

::: tabs#shell
@tab WSL (Bash)
```bash:no-line-numbers
./vendor/bin/phpunit --debug packages/core/src/components/Container/__tests__/ContainerTest.php
```
@tab PowerShell
```powershell:no-line-numbers
./vendor/bin/phpunit --debug packages/core/src/components/Container/__tests__/ContainerTest.php
```
:::

:::details Logging to the console

`print_r` and `var_dump` may not work in unit tests. Instead, you can use:

```php
fwrite(STDERR, 'something to output as an error');
```
```php
fwrite(STDOUT, 'something to output as a basic message');
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

Try running the unit tests with coverage again. If using the PhpStorm run configuration for PHPUnit, select "Run [test config] with coverage" as shown below:

![PHPUnit run with coverage](/phpstorm-run-with-coverage.png)

If it works, the `Coverage` tool window should populate when the tests have finished (and coverage data should be shown in the Project tool window and in the files themselves until you close the active suite in the Coverage tool window).

Below is an example of the test run output and the coverage tool window.

[![PhpStorm test run output and coverage](/phpstorm-coverage-window.png)

:::
