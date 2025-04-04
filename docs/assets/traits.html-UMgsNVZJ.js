import{_ as s,c as a,a as t,o as e}from"./app-DTpylkEk.js";const p={};function o(c,n){return e(),a("div",null,n[0]||(n[0]=[t(`<h1 id="component-traits" tabindex="-1"><a class="header-anchor" href="#component-traits"><span>Component Traits</span></a></h1><p>PHP traits are used to provide common implementations of an attribute&#39;s conversion from <code>$attributes</code> array element to object field. This provides a central location for validation logic and documentation, reducing duplication and ensuring consistency.</p><div class="trait-class-doc"><div><h2 id="backgroundcolor" tabindex="-1"><a class="header-anchor" href="#backgroundcolor"><span>BackgroundColor</span></a></h2><dl><dt>Property</dt><dd><code>backgroundColor</code><strong>Type:</strong> <code>ThemeColor</code><p>Background colour keyword</p></dd><dt>Method</dt><dd><code>set_background_color_from_attrs</code><strong>Returns:</strong> <code>void</code><p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p></dd></dl></div><div class="hint-container note"><p class="hint-container-title">Example usage</p><div class="language-php" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token keyword">namespace</span> <span class="token package">Doubleedesign<span class="token punctuation">\\</span>Comet<span class="token punctuation">\\</span>Core</span><span class="token punctuation">;</span></span>
<span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">MyComponent</span> <span class="token punctuation">{</span></span>
<span class="line">	<span class="token keyword">use</span> <span class="token package">BackgroundColor</span><span class="token punctuation">;</span></span>
<span class="line">	</span>
<span class="line">	<span class="token keyword">function</span> <span class="token function-definition function">__construct</span><span class="token punctuation">(</span><span class="token keyword type-hint">array</span> <span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token keyword type-hint">array</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span> <span class="token punctuation">{</span></span>
<span class="line">		<span class="token keyword static-context">parent</span><span class="token operator">::</span><span class="token function">__construct</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">		<span class="token variable">$this</span><span class="token operator">-&gt;</span><span class="token function">set_background_color_from_attrs</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">	<span class="token punctuation">}</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre></div></div></div><div class="trait-class-doc"><div><h2 id="colortheme" tabindex="-1"><a class="header-anchor" href="#colortheme"><span>ColorTheme</span></a></h2><dl><dt>Property</dt><dd><code>colorTheme</code><strong>Type:</strong> <code>ThemeColor</code><p>Colour keyword for the fill or outline colour</p></dd><dt>Method</dt><dd><code>set_color_theme_from_attrs</code><strong>Returns:</strong> <code>void</code><p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p></dd></dl></div><div class="hint-container note"><p class="hint-container-title">Example usage</p><div class="language-php" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token keyword">namespace</span> <span class="token package">Doubleedesign<span class="token punctuation">\\</span>Comet<span class="token punctuation">\\</span>Core</span><span class="token punctuation">;</span></span>
<span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">MyComponent</span> <span class="token punctuation">{</span></span>
<span class="line">	<span class="token keyword">use</span> <span class="token package">ColorTheme</span><span class="token punctuation">;</span></span>
<span class="line">	</span>
<span class="line">	<span class="token keyword">function</span> <span class="token function-definition function">__construct</span><span class="token punctuation">(</span><span class="token keyword type-hint">array</span> <span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token keyword type-hint">array</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span> <span class="token punctuation">{</span></span>
<span class="line">		<span class="token keyword static-context">parent</span><span class="token operator">::</span><span class="token function">__construct</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">		<span class="token variable">$this</span><span class="token operator">-&gt;</span><span class="token function">set_color_theme_from_attrs</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">	<span class="token punctuation">}</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre></div></div></div><div class="trait-class-doc"><div><h2 id="icon" tabindex="-1"><a class="header-anchor" href="#icon"><span>Icon</span></a></h2><dl><dt>Property</dt><dd><code>iconPrefix</code><strong>Type:</strong> <code>?string</code><p>Icon prefix class name</p></dd><dt>Property</dt><dd><code>icon</code><strong>Type:</strong> <code>?string</code><p>Icon class name</p></dd><dt>Method</dt><dd><code>set_icon_from_attrs</code><strong>Returns:</strong> <code>void</code><p></p></dd></dl></div><div class="hint-container note"><p class="hint-container-title">Example usage</p><div class="language-php" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token keyword">namespace</span> <span class="token package">Doubleedesign<span class="token punctuation">\\</span>Comet<span class="token punctuation">\\</span>Core</span><span class="token punctuation">;</span></span>
<span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">MyComponent</span> <span class="token punctuation">{</span></span>
<span class="line">	<span class="token keyword">use</span> <span class="token package">Icon</span><span class="token punctuation">;</span></span>
<span class="line">	</span>
<span class="line">	<span class="token keyword">function</span> <span class="token function-definition function">__construct</span><span class="token punctuation">(</span><span class="token keyword type-hint">array</span> <span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token keyword type-hint">array</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span> <span class="token punctuation">{</span></span>
<span class="line">		<span class="token keyword static-context">parent</span><span class="token operator">::</span><span class="token function">__construct</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">		<span class="token variable">$this</span><span class="token operator">-&gt;</span><span class="token function">set_icon_from_attrs</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">	<span class="token punctuation">}</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre></div></div></div><div class="trait-class-doc"><div><h2 id="layoutalignment" tabindex="-1"><a class="header-anchor" href="#layoutalignment"><span>LayoutAlignment</span></a></h2><dl><dt>Property</dt><dd><code>hAlign</code><strong>Type:</strong> <code>Alignment</code><p></p></dd><dt>Property</dt><dd><code>vAlign</code><strong>Type:</strong> <code>Alignment</code><p></p></dd><dt>Method</dt><dd><code>set_layout_alignment_from_attrs</code><strong>Returns:</strong> <code>void</code><p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p></dd></dl></div><div class="hint-container note"><p class="hint-container-title">Example usage</p><div class="language-php" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token keyword">namespace</span> <span class="token package">Doubleedesign<span class="token punctuation">\\</span>Comet<span class="token punctuation">\\</span>Core</span><span class="token punctuation">;</span></span>
<span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">MyComponent</span> <span class="token punctuation">{</span></span>
<span class="line">	<span class="token keyword">use</span> <span class="token package">LayoutAlignment</span><span class="token punctuation">;</span></span>
<span class="line">	</span>
<span class="line">	<span class="token keyword">function</span> <span class="token function-definition function">__construct</span><span class="token punctuation">(</span><span class="token keyword type-hint">array</span> <span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token keyword type-hint">array</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span> <span class="token punctuation">{</span></span>
<span class="line">		<span class="token keyword static-context">parent</span><span class="token operator">::</span><span class="token function">__construct</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">		<span class="token variable">$this</span><span class="token operator">-&gt;</span><span class="token function">set_layout_alignment_from_attrs</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">	<span class="token punctuation">}</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre></div></div></div><div class="trait-class-doc"><div><h2 id="layoutcontainersize" tabindex="-1"><a class="header-anchor" href="#layoutcontainersize"><span>LayoutContainerSize</span></a></h2><dl><dt>Property</dt><dd><code>size</code><strong>Type:</strong> <code>ContainerSize</code><p>Keyword specifying the relative width of the container for the inner content</p></dd><dt>Method</dt><dd><code>set_size_from_attrs</code><strong>Returns:</strong> <code>void</code><p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p></dd></dl></div><div class="hint-container note"><p class="hint-container-title">Example usage</p><div class="language-php" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token keyword">namespace</span> <span class="token package">Doubleedesign<span class="token punctuation">\\</span>Comet<span class="token punctuation">\\</span>Core</span><span class="token punctuation">;</span></span>
<span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">MyComponent</span> <span class="token punctuation">{</span></span>
<span class="line">	<span class="token keyword">use</span> <span class="token package">LayoutContainerSize</span><span class="token punctuation">;</span></span>
<span class="line">	</span>
<span class="line">	<span class="token keyword">function</span> <span class="token function-definition function">__construct</span><span class="token punctuation">(</span><span class="token keyword type-hint">array</span> <span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token keyword type-hint">array</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span> <span class="token punctuation">{</span></span>
<span class="line">		<span class="token keyword static-context">parent</span><span class="token operator">::</span><span class="token function">__construct</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">		<span class="token variable">$this</span><span class="token operator">-&gt;</span><span class="token function">set_size_from_attrs</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">	<span class="token punctuation">}</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre></div></div></div><div class="trait-class-doc"><div><h2 id="layoutorientation" tabindex="-1"><a class="header-anchor" href="#layoutorientation"><span>LayoutOrientation</span></a></h2><dl><dt>Property</dt><dd><code>orientation</code><strong>Type:</strong> <code>Orientation</code><p></p></dd><dt>Method</dt><dd><code>set_orientation_from_attrs</code><strong>Returns:</strong> <code>void</code><p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p></dd></dl></div><div class="hint-container note"><p class="hint-container-title">Example usage</p><div class="language-php" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token keyword">namespace</span> <span class="token package">Doubleedesign<span class="token punctuation">\\</span>Comet<span class="token punctuation">\\</span>Core</span><span class="token punctuation">;</span></span>
<span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">MyComponent</span> <span class="token punctuation">{</span></span>
<span class="line">	<span class="token keyword">use</span> <span class="token package">LayoutOrientation</span><span class="token punctuation">;</span></span>
<span class="line">	</span>
<span class="line">	<span class="token keyword">function</span> <span class="token function-definition function">__construct</span><span class="token punctuation">(</span><span class="token keyword type-hint">array</span> <span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token keyword type-hint">array</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span> <span class="token punctuation">{</span></span>
<span class="line">		<span class="token keyword static-context">parent</span><span class="token operator">::</span><span class="token function">__construct</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">		<span class="token variable">$this</span><span class="token operator">-&gt;</span><span class="token function">set_orientation_from_attrs</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">	<span class="token punctuation">}</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre></div></div></div><div class="trait-class-doc"><div><h2 id="textalign" tabindex="-1"><a class="header-anchor" href="#textalign"><span>TextAlign</span></a></h2><dl><dt>Property</dt><dd><code>textAlign</code><strong>Type:</strong> <code>Alignment</code><p></p></dd><dt>Method</dt><dd><code>set_text_align_from_attrs</code><strong>Returns:</strong> <code>void</code><p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p></dd></dl></div><div class="hint-container note"><p class="hint-container-title">Example usage</p><div class="language-php" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token keyword">namespace</span> <span class="token package">Doubleedesign<span class="token punctuation">\\</span>Comet<span class="token punctuation">\\</span>Core</span><span class="token punctuation">;</span></span>
<span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">MyComponent</span> <span class="token punctuation">{</span></span>
<span class="line">	<span class="token keyword">use</span> <span class="token package">TextAlign</span><span class="token punctuation">;</span></span>
<span class="line">	</span>
<span class="line">	<span class="token keyword">function</span> <span class="token function-definition function">__construct</span><span class="token punctuation">(</span><span class="token keyword type-hint">array</span> <span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token keyword type-hint">array</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span> <span class="token punctuation">{</span></span>
<span class="line">		<span class="token keyword static-context">parent</span><span class="token operator">::</span><span class="token function">__construct</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">		<span class="token variable">$this</span><span class="token operator">-&gt;</span><span class="token function">set_text_align_from_attrs</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">	<span class="token punctuation">}</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre></div></div></div><div class="trait-class-doc"><div><h2 id="textcolor" tabindex="-1"><a class="header-anchor" href="#textcolor"><span>TextColor</span></a></h2><dl><dt>Property</dt><dd><code>textColor</code><strong>Type:</strong> <code>ThemeColor</code><p></p></dd><dt>Method</dt><dd><code>set_text_color_from_attrs</code><strong>Returns:</strong> <code>void</code><p>Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.</p></dd></dl></div><div class="hint-container note"><p class="hint-container-title">Example usage</p><div class="language-php" data-highlighter="prismjs" data-ext="php"><pre><code><span class="line"><span class="token keyword">namespace</span> <span class="token package">Doubleedesign<span class="token punctuation">\\</span>Comet<span class="token punctuation">\\</span>Core</span><span class="token punctuation">;</span></span>
<span class="line"><span class="token keyword">class</span> <span class="token class-name-definition class-name">MyComponent</span> <span class="token punctuation">{</span></span>
<span class="line">	<span class="token keyword">use</span> <span class="token package">TextColor</span><span class="token punctuation">;</span></span>
<span class="line">	</span>
<span class="line">	<span class="token keyword">function</span> <span class="token function-definition function">__construct</span><span class="token punctuation">(</span><span class="token keyword type-hint">array</span> <span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token keyword type-hint">array</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span> <span class="token punctuation">{</span></span>
<span class="line">		<span class="token keyword static-context">parent</span><span class="token operator">::</span><span class="token function">__construct</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">,</span> <span class="token variable">$innerComponents</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">		<span class="token variable">$this</span><span class="token operator">-&gt;</span><span class="token function">set_text_color_from_attrs</span><span class="token punctuation">(</span><span class="token variable">$attributes</span><span class="token punctuation">)</span><span class="token punctuation">;</span></span>
<span class="line">	<span class="token punctuation">}</span></span>
<span class="line"><span class="token punctuation">}</span></span>
<span class="line"></span></code></pre></div></div></div>`,10)]))}const l=s(p,[["render",o]]),r=JSON.parse('{"path":"/technical-deep-dives/php-architecture/traits.html","title":"Component traits","lang":"en-AU","frontmatter":{"title":"Component traits","position":2},"headers":[{"level":2,"title":"BackgroundColor","slug":"backgroundcolor","link":"#backgroundcolor","children":[]},{"level":2,"title":"ColorTheme","slug":"colortheme","link":"#colortheme","children":[]},{"level":2,"title":"Icon","slug":"icon","link":"#icon","children":[]},{"level":2,"title":"LayoutAlignment","slug":"layoutalignment","link":"#layoutalignment","children":[]},{"level":2,"title":"LayoutContainerSize","slug":"layoutcontainersize","link":"#layoutcontainersize","children":[]},{"level":2,"title":"LayoutOrientation","slug":"layoutorientation","link":"#layoutorientation","children":[]},{"level":2,"title":"TextAlign","slug":"textalign","link":"#textalign","children":[]},{"level":2,"title":"TextColor","slug":"textcolor","link":"#textcolor","children":[]}],"git":{"updatedTime":1743751485000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":6}],"changelog":[{"hash":"241d4d69a5f4eb46b1ca8764b5e0d98c3500e41c","time":1743751485000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Small doc tweaks"},{"hash":"d480717fb66ed21b79baaa9f40b67ea65ddbaab6","time":1743223231000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"More work on unit testing"},{"hash":"4fda7f5158eab97de8674c8ee0e915539ee998b9","time":1743203983000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add PHP architecture overview; update page order and other tweaks"},{"hash":"12a2778371eb6549f97a535dc5e299c804f4ba2d","time":1742895259000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Feat(Icon): Use a trait for icons"},{"hash":"bd46ea421c43d7241b0682e551b9810fce24a5c8","time":1742709938000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Finish migrating docs to VuePress"},{"hash":"593c083e86d6a8baa7e78e6af98e148d7f5d69b9","time":1742624410000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"More work on new docs; WIP on updating and improving doc generation for abstract classes/traits/types"}]},"filePathRelative":"technical-deep-dives/php-architecture/traits.md"}');export{l as comp,r as data};
