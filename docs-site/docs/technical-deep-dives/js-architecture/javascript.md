---
title: Basic - Vanilla JavaScript
position: 1
---

# Basic JavaScript enhancements

"Vanilla" JavaScript can be used selectively to provide client-side enhancements to Comet Components. 

## Create a JavaScript-enhanced Comet Component
1. [Create a new component](../../development-core/new-component.md) as you would for a standard PHP component.
2. Add a `your-component.js` file to the component directory containing the JavaScript you want to use.
3. Add the file to the `rollup.index.js` file in the core package root directory. This will ensure that the file is included in the bundled JavaScript file that implementations can use.
4. If you don't have a [file watcher](../../local-dev-deep-dives/tooling-guides/phpstorm.md#file-watchers) or other automatic way for Rollup to run configured, run `npm run build` from the core package directory manually to update the `dist.js` file with your new script. 
