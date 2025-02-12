# SASS (SCSS) setup and development approach

[SCSS](https://sass-lang.com/) or SASS (Syntactically Awesome Style Sheets) is a CSS preprocessor that allows the
definition and use of variables,
functions, and
mixins to make CSS more modular and maintainable.

- [Installing and running SASS](#installing-and-running-sass)
- [Development approach](#development-approach)

---

## Installing and running SASS

You can install SASS globally using NPM, which will work regardless of which terminal you are using.

```bash
npm install -g sass
```

But that JavaScript implementation is supposedly slower than Dart Sass on is own. Windows users can use Chocolatey to
install Dart Sass globally:

```PowerShell
choco install sass
```

<details>
<summary>Caveats for Chocolatey + WSL</summary>

However, this can cause some permission and file path headaches when going via WSL and trying to use glob patterns or
the watcher
though (even with an alias in your Bash config). I haven't figured out a good way to get around that yet, because I have
historically always used the NPM version for the command line, and alternatively when using [PHPStorm](./phpstorm.md),
its file watcher can
be configured to use the Chocolatey-installed version.

</details>

To compile a single file and watch for changes (example):

```bash
sass global.scss:global.css --watch
```

To watch all files in a directory with a glob pattern:

```bash
## To come
```

Or if using PHPStorm, you can use its [file watcher](./phpstorm.md#sass-file-watcher).

---

## Development Approach

Vanilla CSS now has a lot of the features that SCSS was created to provide, such as variables and nesting, and even some
colour functions like `lighten` and `darken` can be replicated without SCSS now. Nonetheless, I am using it within the
`core` package for conveniences such as mixins, loops, and neat [BEM](https://getbem.com/) class syntax.

However, my goal is that SCSS will only be required when making changes to the CSS of the `core` package, and
implementations (such as WordPress themes) should be able to change things like colours and fonts without an SCSS
compile step being mandatory. Some ways to achieve this are:

- Generic variable naming (e.g., `primary` not `blue`, based on a colour's place in a brand/palette, not on what colour
  it actually is)
- Using CSS variables (custom properties) where possible, not SCSS variables
- If creating colour functions, they should compile down to vanilla CSS that uses the CSS variables within the output (
  e.g., `lighten` should compile to CSS `color-mix` with a CSS variable in it, not the hex code of the lightened colour
  result)
