---
title: Advanced - Vue.js
position: 2
---

# Vue.js enhanced components

Comet Components is equipped with [Vue SFC Loader](https://github.com/FranckFreiburger/vue3-sfc-loader) to selectively load Vue components. This allows you to use Vue.js for specific components in a self-contained way, without needing to convert your entire application to Vue and without interfering with other existing JavaScript on your page (for example, WordPress form plugins often have their own JS that may not work if you try to load the form within a Vue component).

Use cases for this include:
:::details Client-side interactivity
You can use Vue to sprinkle interactivity into specific components on your page, such as accordions and modals.
:::

:::details Responsive rendering
You can use Vue to respond to viewport or container size changes and render different HTML accordingly. Examples of this can be found in the `SiteHeader` and `ResponsivePanels` components.

This is a response (pun not intended) and solution to the practice of rendering multiple variations of the HTML and using `display:none` to hide the one you don't want at that particular time (which has always felt dirty to me!)
:::

:::important
Vue is awesome and a great way to achieve client-side interactive and responsive features, but it is important to understand that Vue SFC loader essentially turns every component using it into a miniature Vue application. If you're using it so much that you start noticing performance degradation, you might want to just build a full Vue application instead.
:::

:::danger
A limitation of the current implementation is that each Vue-enhanced component can only be used once per page. Not much of a problem for `SiteHeader`, but something that is on the roadmap to be fixed for other components such as `ResponsivePanels`, at which point these docs will be updated to detail how to remove this restriction for new components.

When creating a Vue-enhanced component that can be used as a WordPress block, add `multiple:false` to the `supports` object in `block.json` to impose the restriction within the editor.
:::

## Create a Vue-enhanced Comet Component

1. [Create a new component](../../development-core/new-component.md) as you would for a standard PHP component.
2. In your component's Blade template, add the `data-vue-component` attribute to the root element of your component. This will be the element that Vue mounts to. For example:

```html
<div data-vue-component="responsive-panels">
</div>
```

3. In your component's directory, create a file with the kebab case name of your component, followed by `.vue`. For example, if your component is called `ResponsivePanels`, create a file called `responsive-panels.vue` and put the below boilerplate code into it, updating `ResponsivePanels` to the PascalCase name of your component:

```vue

<script lang="ts">
    export default {
        name: 'ResponsivePanels',
        inheritAttrs: true,
        props: {},
        data() {
            return {};
        },
        mounted() {
        },
        methods: {}
    };
</script>

<template>

</template>

<style lang="css">
	
</style>
```
:::details Notes about this setup
- Version 3 of Vue is used.
- `inheritAttrs: true` allows us to pass down HTML attributes directly from PHP class -> Blade like normal and have them work without the Vue component needing specific handling for them. They will be passed to the first HTML element in the Vue component.
	- If the Vue component contains other Vue components and the first rendered HTML element comes from there (such as with the `ResponsivePanels` component which loads `Accordion` and `Tabs` Vue components), that's where the attributes will go, so long as that child component also has `inheritAttrs: true`.
	- This concept is called [fallthrough attributes](https://vuejs.org/guide/components/attrs).
- TypeScript is supported if you include `lang="ts"` in the `<script>` tag.
- This syntax is the Vue [options API](https://vuejs.org/guide/introduction.html#options-api).
- Only vanilla CSS is currently supported in Comet's implementation of Vue SFC Loader.
  :::

4. Add a `your-component.js` file to the component directory. Copy and paste the code below into it:

```javascript
import * as Vue from '../../plugins/vue-wrapper/src/vue.esm-browser.js';
import { loadModule } from '../../plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { vueSfcLoaderOptions, BASE_PATH } from '../../plugins/vue-wrapper/src/index.js';

Vue.createApp({
	components: {
		ResponsivePanels: Vue.defineAsyncComponent(() => {
			return loadModule(`${BASE_PATH}/src/components/ResponsivePanels/responsive-panels.vue`, vueSfcLoaderOptions);
		}),
	}
}).mount('[data-vue-component="responsive-panels"]');
```

  Update to suit your component by:
  - Updating `ResponsivePanels` on line 7 to the PascalCase name of your component
  - Update the path to the `.vue` file on line 8 to the path to the file you just created in step 3
  - Update the selector on line 11 to match what you put in your Blade template in step 2.

5. Add the `your-component.js` file to the `dist.js` build process in `rollup.index.js` in the core package root directory.


:::note
If you don't have a [file watcher](../../local-dev-deep-dives/tooling-guides/phpstorm.md#file-watchers) or other automatic way for Rollup to run configured, you will need to run `npm run build` from the core package directory manually to update the `dist.js` file with your new script - and do so again whenever you change it.
:::

6. In your component's PHP `render()` method, add the props you want to pass to the Vue component. For example:

```php
function render(): void {
	$blade = BladeService::getInstance();

	echo $blade->make($this->bladeFile, [
		'classes'    => $this->get_filtered_classes(),
		'attributes' => $this->get_html_attributes(),
		'breakpoint' => $this->breakpoint,
		'titles'     => $this->titles,
		'panels'     => $this->panels
	])->render();
}
```

7. In your component's Blade template, render the Vue component using its kebab-case tag and the props you passed through to the render method. For example:

```blade
<div data-vue-component="responsive-panels" @if ($classes) @class($classes) @endif>
    <responsive-panels 
    	@attributes($attributes) 
    	breakpoint="{{ $breakpoint }}" 
    	:titles="{{ json_encode($titles) }}"
        :contents="{{ json_encode($panels) }}">
    </responsive-panels>
</div>
```

8. Go into your `.vue` file and add the props you just added to the Blade template to the `props` of the component. For example:

```vue

<script lang="ts">
    import * as Vue from '../../plugins/vue-wrapper/src/vue.esm-browser.js';
    import type { PanelItem } from './types.ts';

    export default {
        name: 'ResponsivePanels',
        inheritAttrs: true,
        props: {
            titles: {
                type: Array as () => PanelItem[],
                required: true,
            },
            contents: {
                type: Array as () => PanelItem[],
                required: true,
            },
            breakpoint: String
        },
        // ... rest of your component script
    }
</script>
```

:::tip
If you want to separate out inner components and they are ones that will be used in multiple places, those should go in 
`./packages/core/plugins/shared-vue-components` and ideally have their CSS co-located in the Vue file.
:::

9. Add the component to the `web-types.json` file in the Core package root so that PhpStorm recognises the tag as a Vue component, rather than flagging it as an unknown HTML element, and intellisense picks up the props.

10. Go forth and build the rest of your Vue component!

:::details Where to put CSS?
For Vue components that are shared amongst multiple PHP components (for example, the Accordion and Tabs are used in both their standalone counterparts and in `ResponsivePanels`), it is preferred to put the CSS within the Vue single-file component. This simplifies style loading when using non-bundled [asset-loading methods](../../development-new/overview.md#loading-assets), and also means the CSS is only loaded when the component is actually being used.

:warning: That said, also in the spirit of simplifying things (particularly for front-end performance), the current implementation of Vue SFC Loader is not set up to support Sass within Vue components, which can mean a little more code is necessary to get the desired results. In some cases, it may be worthwhile to have a separate SCSS file and manually add it to the asset loader or a custom bundle.
:::

