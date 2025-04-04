import{_ as i,c as l,b as a,a as r,e as s,w as o,r as c,o as d,d as n}from"./app-DTpylkEk.js";const h={},u={class:"table-of-contents"};function p(m,e){const t=c("router-link");return d(),l("div",null,[e[2]||(e[2]=a("h1",{id:"html-and-css-structure-and-approach",tabindex:"-1"},[a("a",{class:"header-anchor",href:"#html-and-css-structure-and-approach"},[a("span",null,"HTML and CSS structure and approach")])],-1)),a("nav",u,[a("ul",null,[a("li",null,[s(t,{to:"#html-and-css"},{default:o(()=>e[0]||(e[0]=[n("HTML and CSS")])),_:1})]),a("li",null,[s(t,{to:"#sass-css-development-approach"},{default:o(()=>e[1]||(e[1]=[n("Sass/CSS development approach")])),_:1})])])]),e[3]||(e[3]=r('<h2 id="html-and-css" tabindex="-1"><a class="header-anchor" href="#html-and-css"><span>HTML and CSS</span></a></h2><p>Atomic design and component-scoped styling carries a risk of having to write styles for absolutely everything, which leads to duplication and inconsistency - or elements needing way too many class names to style them.</p><p>A core principle of Comet Components&#39; HTML construction and associated CSS is balancing the benefits of sensible defaults and shared styles (embracing the C in CSS) with that of component-scoped styling. None of the components are truly standalone in their styling: All require the <code>global</code> CSS file, which provides core foundations; but at the same time, component-specific styles are located in the component&#39;s own CSS file and are scoped using <a href="https://getbem.com/" target="_blank" rel="noopener noreferrer">BEM</a> methodology.</p><p>While there may be some exceptions for practical reasons, the general guiding principles for how Comet Components&#39; HTML (and by association, CSS) is structured are:</p><details class="hint-container details"><summary>What we call it: BEM class naming</summary><p>The <a href="https://getbem.com/" target="_blank" rel="noopener noreferrer">Block-Element-Modifier</a> (BEM) methodology is used for class naming. This is a way of labelling HTML output using class names that:</p><ul><li>makes it clear which component is which when looking at the rendered code</li><li>enables straightforward component-scoped styling and supports reliable JavaScript-based targeting</li><li>enables neat, readable, maintainable Sass nesting.</li></ul></details><details class="hint-container details"><summary>What it is and its current state: ARIA attributes and roles</summary><p>As a general rule, if there is an ARIA attribute or role that can describe a component&#39;s current state, that is preferred over class naming. For example,<br><code>&lt;a class=&quot;menu__item__link&quot; aria-current=&quot;page&quot;&gt;</code> is preferred over<br><code>&lt;a class=&quot;menu__item__link menu__item__link--current&quot;&gt;</code>.</p><p>This is because not only can we grab onto <code>aria-current</code> for styling, but it also provides useful information to assistive technologies, search engines, and any other tools for which easily machine-readable output is useful.</p><p>This is of course a balancing act with the benefits of BEM for styling and ensuring there are no conflicts, so in some places you will see both a role and a class name. For example, in the <code>Tabs</code> component, the <code>TabListItem</code> is output as an <code>li</code> with both a class name and <code>role=&quot;presentation&quot;</code>. This is useful from a CSS perspective because we can use the class name to neatly ensure we don&#39;t accidentally capture other elements within the <code>Tabs</code> that happen to have <code>role=&quot;presentation&quot;</code>.</p></details><details class="hint-container details"><summary>What it looks like: Data attributes</summary><p>In Comet Components, data attributes are used for centralised styling of common, well-scoped, and reasonably generic variation properties (such as background colours) and layout states (such as orientation). The benefits include:</p><ul><li>a single source of sensible defaults and shared styles, which keeps their usage consistent and the CSS maintainable</li><li>those properties are easy to access for JavaScript-based extension and customisation.</li></ul><p>This is a minor departure from a full commitment to BEM. For example, instead of<br><code>class=&quot;tabs tabs--vertical&quot;</code> we use<br><code>class=&quot;tabs&quot; data-orientation=&quot;vertical&quot;</code>.</p><p>In this example, the data attribute approach means:</p><ul><li>common styling to ensure vertical orientation is not repeated between this and other components</li><li>a custom piece of JavaScript could easily be used to swap the orientation of a component in specific client-side scenarios without risk of impacting other class names and styles.</li></ul></details><div class="hint-container info"><p class="hint-container-title">Info</p><p>Naturally there is some crossover and potential conflict when applying the above principles, as we strive to balance the goal of concise, functionally-named, maintainable HTML and CSS with practical realities of both document structure and styling requirements.</p></div><div class="hint-container important"><p class="hint-container-title">Practical summary</p><p>When deciding on HTML tags, class names and attributes for components, ask yourself:</p><ul><li>what it is, structurally speaking</li><li>how it should be interpreted by assistive technologies (whether that necessitates an ARIA attribute/role or not)</li><li>whether a common data attribute is available for the styling you need (or one should be created)</li><li>whether using ARIA attributes and roles for styling would be sufficiently specific <em>or</em> could have unintended consequences because they wouldn&#39;t be specific enough without making a mess of the Sass/CSS (in which case using a class name for styling is better).</li></ul><p>And remember:</p><ul><li>if you&#39;re using a class name for styling, that doesn&#39;t mean you shouldn&#39;t also have ARIA attributes or roles on the element. Accessibility and machine-readability is always of paramount importance.</li></ul></div><h2 id="sass-css-development-approach" tabindex="-1"><a class="header-anchor" href="#sass-css-development-approach"><span>Sass/CSS development approach</span></a></h2><p>Vanilla CSS now has a lot of the features that SCSS was created to provide, such as variables and nesting, and even some colour functions like <code>lighten</code> and <code>darken</code> can be replicated without SCSS now. Nonetheless, it is used within the <code>core</code> package for conveniences such as mixins, loops, and neat <a href="https://getbem.com/" target="_blank" rel="noopener noreferrer">BEM</a> class syntax.</p><p>However, the goal is that SCSS will only be required when making changes to the CSS of the <code>core</code> package, and implementations (such as WordPress themes) should be able to change things like colours and fonts without an SCSS compile step being mandatory. Some ways to achieve this are:</p><details class="hint-container details"><summary>Generic variable naming</summary><p>For example, <code>primary</code> not <code>blue</code>, based on a colour&#39;s place in a brand/palette, not on what colour it actually is. This enables consuming projects to easily apply their own design tokens by simply updating the value, with no need to rename the variable.</p></details><details class="hint-container details"><summary>CSS variables (custom properties)</summary><p>Where possible, CSS variables (custom properties) should be used, not SCSS variables. If creating colour functions, they should compile down to vanilla CSS that uses the CSS variables within the output (e.g., <code>lighten</code> should compile to CSS <code>color-mix</code> with a CSS variable in it, not the hex code of the lightened colour result).</p></details>',14))])}const g=i(h,[["render",p]]),f=JSON.parse('{"path":"/technical-deep-dives/html-css-sass.html","title":"HTML, CSS, and SCSS","lang":"en-AU","frontmatter":{"title":"HTML, CSS, and SCSS"},"headers":[{"level":2,"title":"HTML and CSS","slug":"html-and-css","link":"#html-and-css","children":[]},{"level":2,"title":"Sass/CSS development approach","slug":"sass-css-development-approach","link":"#sass-css-development-approach","children":[]}],"git":{"updatedTime":1742900302000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":2}],"changelog":[{"hash":"8b540960a2d2a19bf7530fc92ee7e0b606d5a3cb","time":1742900302000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Various doc tweaks"},{"hash":"bd46ea421c43d7241b0682e551b9810fce24a5c8","time":1742709938000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Finish migrating docs to VuePress"}]},"filePathRelative":"technical-deep-dives/html-css-sass.md"}');export{g as comp,f as data};
