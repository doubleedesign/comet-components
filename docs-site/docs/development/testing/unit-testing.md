---
title: Unit testing (PHPUnit)
---

# Unit testing

[[toc]]

## About the setup

The unit tests are set up to use PHPUnit with [Patchwork](https://patchwork2.org/) for convenient mocking functions that support class methods.

## Writing tests

### General things to note about PHPUnit

:::details Naming conventions
- Test classes and files should be in PascalCase with the word `Test` appended to the class name. e.g., tests for `Container` are in `ContainerTest.php`.
- Tests are functions within the class, and need to either start with `test_` or be marked with the `#[Test]` attribute.

:::

::: details Debugging
- To log to the console, use `fwrite(STDERR, 'something to output as an error');` or `fwrite(STDOUT, 'something to output as a basic message');`.

:::

### Boilerplate for a new test file

```php
<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\Core;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use DOMDocument;

#[TestDox("YourComponent")]
class YourComponentTest extends CometTestCase {

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

### General debugging

To debug unexpected test failures (or silent failures - where the test passes but you know it shouldn't, the number of
assertions is wrong, etc), you can run PHPUnit with the `--debug` flag. For example:

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

### Logging to the console

`print_r` and `var_dump` may not work in unit tests. Instead, you can use:

```php
fwrite(STDERR, 'something to output as an error');
```
```php
fwrite(STDOUT, 'something to output as a basic message');
```
