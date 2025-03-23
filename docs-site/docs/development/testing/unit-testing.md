# Unit testing

::: warning
// This probably needs updating.
:::

[[toc]]

## Writing tests

::: warning
Details to come
:::

## Running tests

To run a test file from the terminal:

```bash
./vendor/bin/phpunit  test/unit/HeadingTest.php --configuration ./test/phpunit.xml
```

PhpStorm users can:

- Use the included Run configuration to run all tests
- Run selected tests or files from the gutter icon; A PHPUnit run config template has also been included which will be
  used for that unless you configure otherwise.

See [PhpStorm configuration notes](./phpstorm.md) for more information.

## Coverage reporting

You will need [Xdebug](https://xdebug.org/) installed and enabled to generate coverage data and reports. See
the [PHP setup notes](../tooling/php.md) for more information.

A PhpStorm Run Configuration has been included in this repository for running all tests and generating HTML coverage
report. To see coverage data using the IDE coverage tools, use the "Run with coverage" option. By default, this is
located in the top right corner of the PhpStorm window (shown below); you can also find it in the Run menu.

![Run with coverage screenshot](/phpstorm-run-with-coverage.png)]

## About the setup

> [!WARNING]
> The unit tests are set up to use PHPUnit with BrainMonkey, which provides Patchwork and Mockery under the hood with
> convenience functions for mocking, and a bunch of WordPress-specific stuff. The catch is version compatibility
> problems.
> At the time of writing, the latest version of BrainMonkey is 2.6.2 and it is compatible with PHPUnit 9.x. **Newer
versions
> of PHPUnit experience silent failures.** You can find the latest compatibility information
> on [Packagist](https://packagist.org/packages/brain/monkey). Patchwork + PHPUnit compatibility is also something to
> watch out for in the future.

#### Test class hierarchy example

| Class              | Inherits from                | Purpose                                                                                                      |
|--------------------|------------------------------|--------------------------------------------------------------------------------------------------------------|
| `HeadingTest.php`  | `WpBridgeTestCase`           | Unit tests for the Heading component                                                                         |
| `WpBridgeTestCase` | `CometTestCase`              | Sets up the minimum required WordPress code to test compatibility of Comet Components with WordPress blocks. |
| `CometTestCase`    | `PHPUnit\Framework\TestCase` | Common setup for all tests, such as initialising support libraries.                                          |

Unit tests should generally inherit from either `CometTestCase` or `WpBridgeTestCase` (the latter being for testing
integration with WordPress blocks). In the future there will probably be additional second-layer test classes for other
PHP systems. The exception is things that do not require any function mocks.

### Useful links

- [PHPUnit 9.6 docs](https://docs.phpunit.de/en/9.6/)
- [BrainMonkey docs](https://giuseppe-mazzapica.gitbook.io/brain-monkey)

## Troubleshooting

### Logging to the console

`print_r` and `var_dump` may not work here. Instead, you can use:

```php
fwrite(STDERR, 'something to output as an error');
```

```php
fwrite(STDOUT, 'something to output as a basic message');
```

### General debugging

To debug unexpected test failures (or silent failures - where the test passes but you know it shouldn't, the number of
assertions is wrong, etc), you can run PHPUnit with the `--debug` flag. For example:

```bash
$ vendor/bin/phpunit --debug test/unit/HeadingTest.php
```

However, the standard PHP error handling catches problems that come from the JavaScript in the`BlockTransformer` class
used for testing WordPress compatibility. See [BlockTransformer Test Utility](#blocktransformer-test-utility) below for
more information.

If you're still not getting useful enough feedback, you might want to try wrapping your test content in a try-catch
block and outputting the stack trace and error message.

### BlockTransformer test utility

The `BlockTransformer` class exists as a bridge between PHP and JavaScript to enable generating the output of a
component how the WordPress block editor would save it. It does this using a Node script, because the JavaScript-powered
WordPress block editor transforms input to HTML before saving it to the database.

Consequently, running PHPUnit with `--debug` doesn't catch problems that come from this Node script. Fortunately,
there's a simple workaround: Wrap the tests that use this in a try-catch block. For example:

```php
use Throwable;
// ... rest of test class and opening of test method
try {
    $block = parent::$transformer->transform_block('core/heading', $attributes, [$content]);
}
catch (Throwable $e) {
    echo $e->getTraceAsString() . "\n";
    $this->fail($e->getMessage()); 
}
// ... rest of test method and closing of test class
```
