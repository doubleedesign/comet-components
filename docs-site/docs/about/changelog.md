# Releases

:::info
For fun and gratitude, Comet's releases are named after albums that can somehow be associated with a person, event, or even an offhand comment that influenced either the contents of the release itself, or the author's career as a developer.

...Except when I do multiple minor releases close together and forget to properly document them.
:::

[[toc]]

## Current version

### 0.7.0 Wrecking Ball (2 February 2026)

- Remove WordPress theme and plugin for ACF Flexible Modules; gotta Gutenberg from now on
- Remove BannerV2 component as it's no longer used.

If I had progressed to versions > 0 yet, this would be a whole-number upgrade. I don't want to release v1 until I have a lot more tests in place.

---

## Release History

### 0.6.0 (2 February 2026)

#### General breaking changes

- Updated minimum PHP version to 8.4

#### Core library

- Wrap global and common CSS in a @layer and document why
- Add colour pair functionality to core config
- Feat(LinkGroup, CardList): Add layout options (list, grid, inline)
- Feat(ContainerWithNesting): Add basic nested container functionality
- Refactor(Gallery): Use common max-per-row attributes
- Refactor(EventList,EventCard): Improvements and fixes for event display
- Fix(ContentImageAdvanced,ImageComponent): Fixes and improvements for handling cropping
- Fix(ContentImageAdvanced): Handle inline styles in the right place; fix data attribute name
- Fix(Breadcrumbs): Add BEM to links
- Remove some no-longer-used WP affordances
- Various markup, layout, and styling tweaks to core components.

#### WordPress

- New WP Blocks: Separator, Related Pages, CoverImage, new version of Banner block, Image, Featured Posts, Latest Posts, Gallery, Contact Details
- New attribute controls: item count, max per row, layout order, GalleryControls
- Override block.json defaults with site-level component defaults if available
- Option to put shared content in a nested container to allow per-instance size changes
- Add capabilities for rendering third-party blocks wrapped in Comet components
- Render placeholder in editor if block is empty
- Deprecate WP PreprocessedHTML components and create one in Core in its place
- Implement features introduced in my other plugins for CPT Indexes and TinyMCE enhancements
- Move ACF field definitions into PHP for the calendar plugin
- Update Upcoming Events block with new attribute and field approach; update event archive
- Set up for unit testing the calendar plugin
- Various refactoring of the Calendar plugin
- Add minimal theme.json to the block parent theme because without it the editor loads unwanted core styles
- Add blog templates to the block theme
- Fix: Stop loading theme fonts into non-content admin areas when in the block editor
- Various admin/editor fixes and styling tweaks.

### 0.5.0 (30 December 2025)

- Fix(WP Blocks): Automatically open the editing overlay when a new block is added
- Move a lot of TinyMCE custom stuff into a separate plugin
- Bring classic and blocks TinyMCE config more into line.

### 0.4.0 (28 December 2025)

- WP Blocks implementation overhaul:
    - Disable block patterns
    - Upgrade to ACF Blocks v3 + iframed block editor experience
    - Disable all core blocks in favour of fully custom ACF-driven blocks
    - Move ThemeStyle class into the plugin
    - Hide some irrelevant editor options
    - Fix: Properly refresh block previews that use Vue components
    - Feat: Basic blog post template
- Refactor(Core styles): Put global and common styles on a CSS layer to ensure themes can easily override
- Feat(Core): Create and test colour utils; other colour management tweaks
- Fix(Config): Importing of BaguetteBox when compiling with Rollup
- Fix(BladeService): Loading of Blade template overrides
- Various markup, layout, and styling tweaks to core blocks
- Add some more unit tests for core Utils
- Bunch of tweaks and improvements to TinyMCE use in WordPress implementations.

### 0.3.0 (13 December 2025)

- New components: CardList
- Refactor: Rename comet-plugin and comet-canvas to *-blocks
- Refactor(Core SCSS): Add and implement functions for partial class selectors for BEM purposes
- Refactor(Core): Fix inconsistent method privacy
- Refactor(Core): Streamline and centralise setting of context + BEM classes
- Fix(Gallery): Fix layout when captions are present; tweak styling of lightbox plugin
- Fix(BannerV2): Simplification of HTML and fixes to image rendering
- Refactor(Columns, Table): Default to stacking behaviour in small containers/viewports without specifying in the HTML
- WP ACF modules/Classic Editor plugin/theme:
    - Add vertical alignment option for copy-image module
    - Create child pages module and improve featured and latest post modules for consistency between all three
    - Generate excerpts from ACF WYSIWYG fields in modules
    - Allow formats (e.g., lead paragraph) to be used in minimal WYSIWYG fields
    - Correctly pass down ACF module name as container shortName
    - Update category archive markup
    - Single post HTML fixes
- Docs: Remove "inherited" from JSON docs (it was confusing/misleading)
- Various markup, layout, and styling tweaks to core components
- Various dev config fixes.

### 0.2.0 Such Pretty Forks in the Road (18 September 2025)

- Major refactor of the `Image` component into multiple more focused components to better manage valid combinations of attributes: `CoverImage`, `ContentImageBasic`, and `ContentImageAdvanced`
- Addition of the WIP for a ClassicPress / WordPress Classic Editor + Advanced Custom Fields integration plugin and theme
- Addition of Card component
- Addition of PostNav component
- Addition of BannerV2 component and removal of some unused options in original Banner component

### 0.1.1

Minor update that adds some fixes to the `ResponsivePanels` and `FileGroup` components. Change of versioning so I can more easily make minor patches without a full named release.

### 0.0.3 Rocket Man (30 July 2025)

- Standalone packages for `ResponsivePanels` and `FileGroup`, and the supporting `Launchpad` package
- Scripts for generating/updating standalone packages
- Updates to `Config` and `BladeService` to support specifying where to find Blade templates (necessary for standalone packages to work).

### 0.0.2 London Calling (10 April 2025)

This second alpha release is focused on completion and refinement of the initial set of Vue-enhanced components, notably completion of `ResponsivePanels` and refactoring of `Accordion` and `Tabs` to use the same Vue components, ensuring consistency and removing Bootstrap from the project dependencies.

### 0.0.1 Fearless (6 April 2025)

A "soft release" of the "incomplete alpha" version of Comet Components, marked for the day I turned the [GitHub repo](https://github.com/doubleedesign/comet-components) public and published the [Core library on Packagist](https://packagist.org/packages/doubleedesign/comet-components-core), a few days after first publishing the [documentation site](https://cometcomponents.io).

- Components that correspond directly to 21 WordPress core blocks
- Plus other components: Container, Accordion, Tabs, Call-to-Action, SiteHeader, SiteFooter, Menu, FileGroup, LinkGroup
- WordPress plugin integrating the 21 core blocks as well as blocks for the majority of the other components
- WordPress parent theme integrating SiteHeader, SiteFooter, and Menu
- Dev tooling including scripts to generate skeleton code for new components, generate JSON files describing components (which also act as a way to manually confirm that fields are correctly defined/implemented), and generate the skeleton code for stories
- Enhancement of the SiteHeader component using Vue.js
- Some unit and integration tests
- Almost-complete documentation website.
