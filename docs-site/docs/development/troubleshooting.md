# Troubleshooting

[[toc]]

## Local development environment

::: details Where is PHP, Node, Composer, etc running from?
::: tabs#shell
@tab WSL (Bash)
```bash:no-line-numbers
readlink -f $(which php)
```
```bash:no-line-numbers
readlink -f $(which composer)
```
```bash:no-line-numbers
which node
```
```bash:no-line-numbers
which sass
```
@tab PowerShell
```powershell:no-line-numbers
Get-Command php
```
```powershell:no-line-numbers
Get-Command composer
```
```powershell:no-line-numbers
Get-Command node
```
```powershell:no-line-numbers
Get-Command sass
```
@tab PhpStorm
Settings > PHP > CLI Interpreter
![phpstorm-php.png](/phpstorm-php.png)

Settings > PHP > Composer
![phpstorm-composer.png](/phpstorm-composer.png)

If the **Composer executable** is set to just `composer` like in the above example, it is using the global Composer installation which you can find using the
terminal commands.

Settings > Languages & Frameworks > Node.js
![phpstorm-node.png](/phpstorm-node.png)
:::

:::details Where is my PHP configuration (php.ini) file?
:warning: **Note:** The browser dev/testing envrionment run with `npm run test:server` uses its own, separate config file. Similarly if you are developing a WordPress site using Local by Flywheel, your site will use the config set by Local.

The below commands will show the configuration for your command line and/or IDE instance of PHP.
::: tabs#shell
@tab WSL (Bash)
```bash:no-line-numbers
php --ini
```
@tab PowerShell
```powershell:no-line-numbers
php --ini
```
@tab PhpStorm
1. Go to File > Settings > Languages & Frameworks > PHP > CLI Interpreter
2. Click the `...` button next to the interpreter path
3. In the dialog that appears, there will be a field to set the configuration file path. **You do not need to do this if
   it has automatically been detected.** Keep reading - look for the blue info icon with the configuration path below
   that. An example is pictured below.
   ![phpstorm-php.png](../.vuepress/public/phpstorm-phpini.png)

:::

::: details "Is not a valid Win32 application" error when using a PhpStorm file watcher
If this error is occurring for an NPM package and you ran `npm install` from WSL, it may not have installed the Windows binaries in the `node_modules/.bin` directory for the tool you're trying to use. There are two workarounds:

1. Switch to PowerShell and run `npm install` again. Being a native Windows shell, it will install the Windows binaries.
2. In the file watcher configuration, set the `Program` to `node` and put the full CLI command for the tool in the `arguments` field.

If this is occurring for Sass, consider [installing Sass natively using Chocolatey](./tooling/sass.md) and setting the file watcher to use that.
:::

::: details Missing syntax highlighting in TypeScript files
Make sure the package containing definitions is listed in the root `tsconfig.json` file. For example, JS testing tools such as Playwright need to be added here for syntax highlighting to work in PhpStorm, like so:

```json
{
	"compilerOptions": {
		"types": [
			"node",
			"playwright"
		]
	}
}
```

In PhpStorm, you may also need to:
- add Playwright to the JS libraries under `Settings > Languages & Frameworks > JavaScript > Libraries`. Include all of `@playwright/test`, `playwright`, and `playwright-core`
- Uncheck "use types from server" in the TypeScript settings under `Settings > Languages & Frameworks > TypeScript`
- You may also need to restart the TypeScript service and wait a minute or so to ensure the changes take effect, and/or close and reopen any Playwright files currently open. The restart option is usually located in the bottom right of the IDE. If it's still not refreshing, try invalidating caches and restarting the IDE (`File > Invalidate Caches / Restart...`).
-
:::

::: details Missing Playwright browsers in Windows
In a separate PowerShell window with admin privileges:

```powershell:no-line-numbers
npx playwright install firefox
```
:::

## Front-end development

::: details Component JavaScript not loading in the browser
Is the script loaded either independently, or as part of the `dist.js` bundle? If not, either:

- Add a script tag (or in WordPress, `wp_enqueue_script`) to load it, remembering that you may need to put the `type="module"` attribute on the script tag
- Add the script to `rollup.index.js` and run `npm run build`
- If the script is to be included in the bundle, ensure the import path remapping in `rollup.config.js` will resolve any imports in the file correctly.

If the script is listed in `rollup.index.js` and the import path transformation seems correct, try running `npm run build` - maybe the file watcher didn't auto-compile it when expected.
:::

::: details Sass not recompiling when an imported file changes
In PhpStorm, try running "reload all from disk" to make it realise the file has changed. If that doesn't help, make a whitespace change in the file you want to recompile (not the one that's imported, that you already actually changed).

:::
