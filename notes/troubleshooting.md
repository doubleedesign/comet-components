# Miscellaneous troubleshooting notes

- [PHP unit tests](#php-unit-tests)
  - [Logging to the console](#logging-to-the-console)
  - [General debugging](#general-debugging)
  - [BlockTransformer test utility](#blocktransformer-test-utility)
- [Where is PHP, Node, Composer, etc running from?](#where-is-php-node-composer-etc-running-from)


## PHP unit tests

### Logging to the console

`print_r` and `var_dump` may not work here. Instead, you can use:
    
```php
fwrite(STDERR, 'something to output as an error');
```
```php
fwrite(STDOUT, 'something to output as a basic message');
```

### General debugging

To debug unexpected test failures (or silent failures - where the test passes but you know it shouldn't, the number of assertions is wrong, etc), you can run PHPUnit with the `--debug` flag. For example:

```bash
$ vendor/bin/phpunit --debug test/unit/HeadingTest.php
```

However, the standard PHP error handling catches problems that come from the JavaScript in the`BlockTransformer` class used for testing WordPress compatibility. See [BlockTransformer Test Utility](#blocktransformer-test-utility) below for more information.

If you're still not getting useful enough feedback, you might want to try wrapping your test content in a try-catch block and outputting the stack trace and error message.


### BlockTransformer test utility

The `BlockTransformer` class exists as a bridge between PHP and JavaScript to enable generating the output of a component how the WordPress block editor would save it. It does this using a Node script, because the  JavaScript-powered WordPress block editor transforms input to HTML before saving it to the database.

Consequently, running PHPUnit with `--debug` doesn't catch problems that come from this Node script. Fortunately, there's a simple workaround: Wrap the tests that use this in a try-catch block. For example:

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

--
## Where is PHP, Node, Composer, etc running from?

Check in PowerShell:
```PowerShell
Get-Command php
```
```PowerShell
Get-Command node
```
```PowerShell
Get-Command composer
```
Or in WSL:
```bash
readlink -f $(which php)
```
```bash
readlink -f $(which node)
```
```bash
readlink -f $(which composer)
``` 
