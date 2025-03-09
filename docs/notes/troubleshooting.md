# Miscellaneous troubleshooting notes

- [PHP unit tests](#php-unit-tests)
    - [Logging to the console](#logging-to-the-console)
    - [General debugging](#general-debugging)
    - [BlockTransformer test utility](#blocktransformer-test-utility)
- [Where is PHP, Node, Composer, etc running from?](#where-is-php-node-composer-etc-running-from)
- [Where is my PHP configuration (php.ini) file?](#where-is-my-php-configuration-phpini-file)
- [PHPStorm File Watchers](#phpstorm-file-watchers)
- [Front-end development](#front-end-development)

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

```PowerShell
Get-Command sass
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

```bash
where sass
``` 

## Where is my PHP configuration (php.ini) file?

From the command line:

```PowerShell
php --ini
```

PHPStorm:

- Go to `File > Settings > Languages & Frameworks > PHP > CLI Interpreter`
- Click the `...` button next to the interpreter path
- In the dialog that appears, there will be a field to set the configuration file path. **You do not need to do this if
  it has automatically been detected.** Keep reading - look for the blue info icon with the configuration path below
  that. An example is pictured below.

![phpstorm-node.png](images/phpstorm-phpini.png)

> [!NOTE]
> The browser dev/testing envrionment run with `npm run test:server` uses its own, separate config file.

## PHPStorm File Watchers

**"Is not a valid Win32 application" error**"

If you ran `npm install` from WSL, it may not have installed the Windows binaries in the `node_modules/.bin` directory
for the tool you're trying to use. There are two workarounds:

1. Switch to PowerShell or Command Prompt and run `npm install` again. Being the native Windows shell, it will install
   the Windows binaries.
2. In the file watcher configuration, set the `Program` to `node` and put the full CLI command for the tool in the
   `arguments` field.

---

## Front-end development

**Component JavaScript not loading in the browser**

Is the script loaded either independently, or as part of the `dist.js` bundle? If not, either:

- Add a script tag (or in WordPress, `wp_enqueue_script`) to load it, remembering that you may need to put the
  `type="module"` attribute on the script tag
- Add the script to `rollup.index.js` and run `npm run build`
- If the script is to be included in the bundle, ensure the import path remapping in `rollup.config.js` will resolve any
  imports in the file correctly.

If the script is listed in `rollup.index.js` and the import path transformation seems correct, try running
`npm run build` - maybe the file watcher didn't auto-compile
it when expected.

**SCSS not recompiling when an imported file changes**

In PHPStorm, try running "reload all from disk" to make it realise the file has changed. If that doesn't help, make a
whitespace change in the file you want to recompile (not the one that's imported, that you already actually changed).
