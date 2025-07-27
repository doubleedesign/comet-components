# Core Standalone Packages

For dev, standalone packages are generated using symlinks to prevent duplication and divergence of code. These are not committed to Git because Git and symlinks do not get along easily.

To generate a standalone package for dev, run:

```powershell
php scripts/create-standalone-package.php --component=YourComponentName
```

To make a real-files, ready-to-use-elsewhere package, run:

```powershell
php scripts/export-standalone-package.php --component=YourComponentName
```

The latter are stored in the `dist` folder of the standalone package which is uploaded to Git.

To set one up to be split to a standalone repository for Composer installation or easy download from GitHub, add it to the `./github/workflows/split-core-standalone.yml` file or create a similar action.

