import{_ as c,c as r,b as a,a as d,e as s,w as n,d as t,r as l,o as p}from"./app-kQtp0ML3.js";const u={},h={class:"table-of-contents"},g={class:"hint-container warning"};function m(b,e){const o=l("router-link"),i=l("RouteLink");return p(),r("div",null,[e[10]||(e[10]=a("h1",{id:"theming",tabindex:"-1"},[a("a",{class:"header-anchor",href:"#theming"},[a("span",null,"Theming")])],-1)),a("nav",h,[a("ul",null,[a("li",null,[s(o,{to:"#global-php-configuration"},{default:n(()=>e[0]||(e[0]=[t("Global PHP configuration")])),_:1}),a("ul",null,[a("li",null,[s(o,{to:"#global-background-colour"},{default:n(()=>e[1]||(e[1]=[t("Global background colour")])),_:1})]),a("li",null,[s(o,{to:"#default-icon-prefix"},{default:n(()=>e[2]||(e[2]=[t("Default icon prefix")])),_:1})])])]),a("li",null,[s(o,{to:"#global-css-configuration"},{default:n(()=>e[3]||(e[3]=[t("Global CSS configuration")])),_:1}),a("ul",null,[a("li",null,[s(o,{to:"#default-design-tokens-css-variables"},{default:n(()=>e[4]||(e[4]=[t("Default design tokens (CSS variables)")])),_:1})])])]),a("li",null,[s(o,{to:"#adding-animation"},{default:n(()=>e[5]||(e[5]=[t("Adding animation")])),_:1})])])]),e[11]||(e[11]=d('<h2 id="global-php-configuration" tabindex="-1"><a class="header-anchor" href="#global-php-configuration"><span>Global PHP configuration</span></a></h2><p>The <code>Config</code> class provides static methods to set and get several options to be made available to components at runtime, such as global background colour and default icon prefix. In general usage, these can be set directly.</p><p>In WordPress, themes (other than Comet Canvas) don&#39;t generally have direct access to the <code>Config</code> class, unless you have installed the Comet Components core library yourself. Comet Canvas provides filters so that child themes can easily override the defaults from <code>functions.php</code>.</p><h3 id="global-background-colour" tabindex="-1"><a class="header-anchor" href="#global-background-colour"><span>Global background colour</span></a></h3><div class="hint-container important"><p class="hint-container-title">Important</p><p>By setting the global background colour in the Comet config, you are making it available to all components at runtime, allowing them to use this context to refine styling.</p><p>Simply adding the colour to the body tag manually will not have the full effect, because the PHP component classes will not be aware of it when constructing component instances.</p></div>',5)),a("div",g,[e[9]||(e[9]=a("p",{class:"hint-container-title"},"Warning",-1)),a("p",null,[e[7]||(e[7]=t("The string must be a colour name (not a hex code) matching the value of a ")),s(i,{to:"/technical-deep-dives/php-architecture/data-types.html#themecolor"},{default:n(()=>e[6]||(e[6]=[t("ThemeColor")])),_:1}),e[8]||(e[8]=t("."))])]),e[12]||(e[12]=d(`<details class="hint-container details"><summary>General usage</summary><p>You can set a global background colour for your site using this line of code in an appropriate location.</p><p>For a &quot;vanilla&quot; project this could be as simple as calling it at the top of the page:</p><div class="language-php line-numbers-mode" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token class-name static-context">Config</span><span class="token operator">::</span><span class="token function">set_global_background</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;dark&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><p>You then simply need to add the <code>data-global-background</code> attribute to the <code>body</code> tag:</p><div class="language-php line-numbers-mode" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token php language-php"><span class="token delimiter important">&lt;?php</span></span>
<span class="line"><span class="token variable">$globalBackground</span> <span class="token operator">=</span> <span class="token class-name static-context">Config</span><span class="token operator">::</span><span class="token function">get_global_background</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line"><span class="token delimiter important">?&gt;</span></span></span>
<span class="line"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>body</span> <span class="token attr-name">data-global-background</span><span class="token attr-value"><span class="token punctuation attr-equals">=</span><span class="token punctuation">&quot;</span><span class="token php language-php"><span class="token delimiter important">&lt;?php</span> <span class="token keyword">echo</span> <span class="token variable">$globalBackground</span><span class="token punctuation">;</span> <span class="token delimiter important">?&gt;</span></span><span class="token punctuation">&quot;</span></span><span class="token punctuation">&gt;</span></span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div></div></div></details><details class="hint-container details"><summary>In WordPress</summary><p>Comet Canvas provides a filter to override the default (white) in your child theme&#39;s <code>functions.php</code> like so:</p><div class="language-php line-numbers-mode" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token function">add_filter</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;comet_canvas_global_background&#39;</span><span class="token punctuation">,</span> <span class="token keyword">fn</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token string single-quoted-string">&#39;dark&#39;</span><span class="token punctuation">,</span> <span class="token number">20</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div></details><h3 id="default-icon-prefix" tabindex="-1"><a class="header-anchor" href="#default-icon-prefix"><span>Default icon prefix</span></a></h3><p>Components that render icons do so using the <code>Icon</code> trait, which uses two attributes passed from the component <code>$attributes</code>: <code>icon</code> and an optional <code>iconPrefix</code>. These values are usually passed to a HTML <code>&lt;i&gt;</code> element as class names in the component&#39;s Blade template.</p><p>The default <code>iconPrefix</code> is Font Awesome&#39;s <code>fa-solid</code>, but this can be overridden both at the global configuration level and at component level. For example if you want to use <code>fa-light</code> by default, but then <code>fa-solid</code> for a specific component, you can do so by setting <code>fa-light</code> in the global configuration and then passing <code>fa-solid</code> in the special component&#39;s <code>$attributes</code>.</p><details class="hint-container details"><summary>General usage</summary><p>You can set a default icon prefix for your site using this line of code in an appropriate location:</p><div class="language-php line-numbers-mode" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token class-name static-context">Config</span><span class="token operator">::</span><span class="token function">set_icon_prefix</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;fa-duotone fa-solid&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div></details><details class="hint-container details"><summary>In WordPress</summary><p>Comet Canvas provides a filter to override the default icon prefix in your child theme&#39;s <code>functions.php</code> like so:</p><div class="language-php line-numbers-mode" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token function">add_filter</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;comet_canvas_default_icon_prefix&#39;</span><span class="token punctuation">,</span> <span class="token keyword">fn</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token string single-quoted-string">&#39;fa-light&#39;</span><span class="token punctuation">,</span> <span class="token number">20</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div></details><h2 id="global-css-configuration" tabindex="-1"><a class="header-anchor" href="#global-css-configuration"><span>Global CSS configuration</span></a></h2><p>Many design tokens are set as CSS variables in Comet&#39;s stylesheets, allowing you to easily override them without any special tooling. Sass can be useful for calculating values (such as for the readable colour variables) but it is not essential. You can hit the ground running with a simple vanilla stylesheet that sets the variables you want to change. The full list of tokens can be found in the <code>:root</code> selector in <a href="https://github.com/doubleedesign/comet-components/blob/master/packages/core/src/components/global.css" target="_blank" rel="noopener noreferrer">global.css</a> and in the list below.</p><div class="hint-container important"><p class="hint-container-title">Important</p><p>For WordPress usage, some tokens should be set in your theme&#39;s <code>theme.json</code> so that they take effect in the editor as well.</p></div><details class="hint-container details"><summary>General usage</summary><p>Set colours, fonts, spacing, font weights, and container widths by overriding Comet&#39;s CSS variables in your own <code>style.css</code> file. For example:</p><ul><li>Set fonts with the <code>--font-family-body</code> and <code>--font-family-accent</code> variables</li><li>Set colours with the <code>--color-primary</code>, <code>--color-secondary</code>, etc. variables (unless you are using WordPress - see below).</li></ul></details><details class="hint-container details"><summary>In WordPress</summary><ul><li>Set colours and gradients in your theme&#39;s <code>theme.json</code> file. WordPress will automatically pick up on these for use in the editor, and the Comet Canvas theme will inject them as CSS variables in the relevant places (overriding the Comet default ones).</li><li>Set fonts and other CSS variables as per the general usage instructions above.</li></ul></details><h3 id="default-design-tokens-css-variables" tabindex="-1"><a class="header-anchor" href="#default-design-tokens-css-variables"><span>Default design tokens (CSS variables)</span></a></h3><div class="hint-container tip"><p class="hint-container-title">Tips</p><p>The working proposal for <a href="https://drafts.csswg.org/css-color-6/#colorcontrast" target="_blank" rel="noopener noreferrer">CSS Color Module Level 6</a> includes bringing colour contrast functions to vanilla CSS. When this is finalised and hits widespread browser support, the HSL and &quot;readable colour&quot; Sass calculations will likely be replaced with these, removing the need to calculate them in SCSS or manually.</p></div><details class="hint-container details"><summary>Colours</summary><table><thead><tr><th>Colour variable name</th><th>Default value</th><th>Readable*</th></tr></thead><tbody><tr><td><code>--color-primary</code></td><td><span style="background:#845ec2;" class="docs-token-color"></span> <code>#845ec2</code></td><td><code>white </code></td></tr><tr><td><code>--color-secondary</code></td><td><span style="background:#00c9a7;" class="docs-token-color"></span> <code>#00c9a7</code></td><td><code>white </code></td></tr><tr><td><code>--color-accent</code></td><td><span style="background:#ba3caf;" class="docs-token-color"></span> <code>#ba3caf</code></td><td><code>white </code></td></tr><tr><td><code>--color-info</code></td><td><span style="background:#00d2fc;" class="docs-token-color"></span> <code>#00d2fc</code></td><td><code>white </code></td></tr><tr><td><code>--color-warning</code></td><td><span style="background:#f9c971;" class="docs-token-color"></span> <code>#f9c971</code></td><td><code>black </code></td></tr><tr><td><code>--color-success</code></td><td><span style="background:#00c9a7;" class="docs-token-color"></span> <code>#00c9a7</code></td><td><code>white </code></td></tr><tr><td><code>--color-error</code></td><td><span style="background:#d23e3e;" class="docs-token-color"></span> <code>#d23e3e</code></td><td><code>white </code></td></tr><tr><td><code>--color-light</code></td><td><span style="background:#f0f0f2;" class="docs-token-color"></span> <code>#f0f0f2</code></td><td><code>black </code></td></tr><tr><td><code>--color-dark</code></td><td><span style="background:#4b4453;" class="docs-token-color"></span> <code>#4b4453</code></td><td><code>white </code></td></tr></tbody></table><p>*Corresponding &quot;readable&quot; variable e.g., <code>--readable-color-primary</code>.</p><p>See <a href="https://github.com/doubleedesign/comet-components/blob/master/packages/core/src/components/_variables.scss" target="_blank" rel="noopener noreferrer">_variables.scss</a> and <a href="https://github.com/doubleedesign/comet-components/blob/master/packages/core/src/components/global.scss" target="_blank" rel="noopener noreferrer">global.scss</a> for how the readable colours are calculated for the default colours using Sass. You can copy this into your own SCSS to calculate the overrides for your own colours.</p></details><details class="hint-container details"><summary>Typography</summary><table><thead><tr><th>Variable name</th><th>Default value</th></tr></thead><tbody><tr><td><code>--font-family-body</code></td><td><code>sans-serif</code></td></tr><tr><td><code>--font-family-accent</code></td><td><code>sans-serif</code></td></tr><tr><td><code>--font-size-sm</code></td><td><code>0.875rem</code></td></tr><tr><td><code>--font-weight-light</code></td><td><code>300</code></td></tr><tr><td><code>--font-weight-regular</code></td><td><code>400</code></td></tr><tr><td><code>--font-weight-semibold</code></td><td><code>600</code></td></tr><tr><td><code>--font-weight-bold</code></td><td><code>700</code></td></tr></tbody></table></details><details class="hint-container details"><summary>Spacing</summary><p>The default spacing values use an <a href="https://www.modularscale.com/" target="_blank" rel="noopener noreferrer">augmented fourth scale</a> from small upwards.</p><table><thead><tr><th>Variable name</th><th>Default value</th><th></th></tr></thead><tbody><tr><td><code>--spacing-xxs</code></td><td><code>0.25rem</code></td><td><span style="width:0.25rem;" class="docs-token-spacing"></span></td></tr><tr><td><code>--spacing-xs</code></td><td><code>0.5rem</code></td><td><span style="width:0.5rem;" class="docs-token-spacing"></span></td></tr><tr><td><code>--spacing-sm</code></td><td><code>0.707rem</code></td><td><span style="width:0.707rem;" class="docs-token-spacing"></span></td></tr><tr><td><code>--spacing-md</code></td><td><code>1rem</code></td><td><span style="width:1rem;" class="docs-token-spacing"></span></td></tr><tr><td><code>--spacing-lg</code></td><td><code>1.414rem</code></td><td><span style="width:1.414rem;" class="docs-token-spacing"></span></td></tr><tr><td><code>--spacing-xl</code></td><td><code>2rem</code></td><td><span style="width:2rem;" class="docs-token-spacing"></span></td></tr><tr><td><code>--spacing-xxl</code></td><td><code>2.827rem</code></td><td><span style="width:2.827rem;" class="docs-token-spacing"></span></td></tr></tbody></table></details><details class="hint-container details"><summary>Container widths</summary><table><thead><tr><th>Variable name</th><th>Default value</th></tr></thead><tbody><tr><td><code>--width-wide</code></td><td><code>1440px</code></td></tr><tr><td><code>--width-contained</code></td><td><code>1100px</code></td></tr><tr><td><code>--width-narrow</code></td><td><code>960px </code></td></tr><tr><td><code>--width-small</code></td><td><code>600px</code></td></tr></tbody></table></details><details class="hint-container details"><summary>Miscellaneous</summary><table><thead><tr><th>Variable name</th><th>Default value</th></tr></thead><tbody><tr><td><code>--button-border-radius</code></td><td><code>0</code></td></tr></tbody></table></details><h2 id="adding-animation" tabindex="-1"><a class="header-anchor" href="#adding-animation"><span>Adding animation</span></a></h2><div class="hint-container warning"><p class="hint-container-title">Warning</p><p>// TODO examples of Animate-Into-View, Hover.css, etc</p></div>`,21))])}const k=c(u,[["render",m]]),v=JSON.parse('{"path":"/usage/theming.html","title":"Theming","lang":"en-AU","frontmatter":{"position":1},"headers":[{"level":2,"title":"Global PHP configuration","slug":"global-php-configuration","link":"#global-php-configuration","children":[{"level":3,"title":"Global background colour","slug":"global-background-colour","link":"#global-background-colour","children":[]},{"level":3,"title":"Default icon prefix","slug":"default-icon-prefix","link":"#default-icon-prefix","children":[]}]},{"level":2,"title":"Global CSS configuration","slug":"global-css-configuration","link":"#global-css-configuration","children":[{"level":3,"title":"Default design tokens (CSS variables)","slug":"default-design-tokens-css-variables","link":"#default-design-tokens-css-variables","children":[]}]},{"level":2,"title":"Adding animation","slug":"adding-animation","link":"#adding-animation","children":[]}],"git":{"updatedTime":1744288765000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":8}],"changelog":[{"hash":"85415c08315533d6f7be33045ecdd8761ea928fa","time":1744288765000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Refine and document CSS variables"},{"hash":"a15837ec48fdd77da4ce7e31325a9252db56251f","time":1744278659000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Feature: Some rudimentary first steps on dynamic asset loading; Refactor: Rename the config class"},{"hash":"a2519aef7d7ea6c5cda3c66142eba8ed17fcf14a","time":1743250619000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Fix(Docs): Fix unwanted hard wraps; Docs: Add links to intro"},{"hash":"4fda7f5158eab97de8674c8ee0e915539ee998b9","time":1743203983000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add PHP architecture overview; update page order and other tweaks"},{"hash":"8b540960a2d2a19bf7530fc92ee7e0b606d5a3cb","time":1742900302000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Various doc tweaks"},{"hash":"12a2778371eb6549f97a535dc5e299c804f4ba2d","time":1742895259000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Feat(Icon): Use a trait for icons"},{"hash":"ab3c3bcfa59f8aabfd3681ca4c8821daa46ca809","time":1742866034000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Feat(Theming): Option to set a background on the body tag that components will be aware of"},{"hash":"593c083e86d6a8baa7e78e6af98e148d7f5d69b9","time":1742624410000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"More work on new docs; WIP on updating and improving doc generation for abstract classes/traits/types"}]},"filePathRelative":"usage/theming.md"}');export{k as comp,v as data};
