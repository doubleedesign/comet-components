# Comet Components WordPress plugin

This plugin is a WordPress implementation of Double-E Design's Comet Components library and standard customisations of
the block editor itself, and is a foundational requirement for all Double-E Design themes developed for the block editor
from December 2024.

---

## Developer notes

### File structure

Due to the way that some things for the block editor can be done in PHP and others must be done in JavaScript, some of
the code organisation feels a bit unintuitive because there can be code in either language that deals with the same
domain. The following table outlines the purpose of each core file or pair of files in this plugin.

| PHP file                       | JS or other file         | Purpose                                                                                                                                                                                                               |
|--------------------------------|--------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `JavaScriptImplementation.php` | -                        | Provides an explicit structure for pairing PHP and JS files that do related things, and handles enqueueing of the JS file in the WordPress admin.                                                                     |
| `BlockRegistry.php`            | `block-registry.js`      | Manages block registration/availability, rendering, attributes, styles, and variations.                                                                                                                               |
| `BlockEditorConfig.php`        | `block-editor-config.js` | Customisations to the organisation of blocks in the editor such as categorisation, labels and descriptions, and restrictions on parent/child block relationships; and disabling unwanted/unsupported editor features. |
| -                              | `block-editor-hacks.js`  | Customisations for the block editor interface broadly (e.g., sidebars and panels)                                                                                                                                     |
| `BlockEditorAdminAssets.php`   | -                        | Handles loading of shared JS and CSS files in the block editor, including the "editor-hacks" file, shared block CSS, etc.                                                                                             |
| -                              | `block-support.json`     | Centralised reference for supported core blocks, categorisation, etc. Imported into the above files as appropriate.                                                                                                   |

