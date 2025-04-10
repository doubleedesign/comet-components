import{_ as i,c as l,b as t,a as c,d as a,e as o,w as s,r as n,o as h}from"./app-kQtp0ML3.js";const u={},m={class:"hint-container info"},g={class:"table-of-contents"};function p(f,e){const r=n("RouteLink"),d=n("router-link");return h(),l("div",null,[e[13]||(e[13]=t("h1",{id:"overview",tabindex:"-1"},[t("a",{class:"header-anchor",href:"#overview"},[t("span",null,"Overview")])],-1)),t("div",m,[e[5]||(e[5]=t("p",{class:"hint-container-title"},"Info",-1)),e[6]||(e[6]=t("p",null,"This section of the docs covers developing the Comet Components core library itself.",-1)),t("p",null,[e[2]||(e[2]=a("Developers looking to use the library in their projects should refer to the ")),o(r,{to:"/usage/overview.html"},{default:s(()=>e[0]||(e[0]=[a("Usage")])),_:1}),e[3]||(e[3]=a(" and ")),o(r,{to:"/development-new/overview.html"},{default:s(()=>e[1]||(e[1]=[a("New Implementations")])),_:1}),e[4]||(e[4]=a(" sections."))])]),t("nav",g,[t("ul",null,[t("li",null,[o(d,{to:"#tech-stack"},{default:s(()=>e[7]||(e[7]=[a("Tech Stack")])),_:1}),t("ul",null,[t("li",null,[o(d,{to:"#core-technologies"},{default:s(()=>e[8]||(e[8]=[a("Core Technologies")])),_:1})]),t("li",null,[o(d,{to:"#third-party-plugins"},{default:s(()=>e[9]||(e[9]=[a("Third-party plugins")])),_:1})])])]),t("li",null,[o(d,{to:"#dev-tooling"},{default:s(()=>e[10]||(e[10]=[a("Dev Tooling")])),_:1})]),t("li",null,[o(d,{to:"#directory-structure"},{default:s(()=>e[11]||(e[11]=[a("Directory structure")])),_:1})]),t("li",null,[o(d,{to:"#glossary-of-terms"},{default:s(()=>e[12]||(e[12]=[a("Glossary of terms")])),_:1})])])]),e[14]||(e[14]=c('<h2 id="tech-stack" tabindex="-1"><a class="header-anchor" href="#tech-stack"><span>Tech Stack</span></a></h2><h3 id="core-technologies" tabindex="-1"><a class="header-anchor" href="#core-technologies"><span>Core Technologies</span></a></h3><table><thead><tr><th>Technology</th><th>Description</th><th>Details/Rationale</th></tr></thead><tbody><tr><td>PHP</td><td>Primary programming language</td><td>I like it. And because I&#39;m using this in WordPress.</td></tr><tr><td>Blade</td><td>Templating engine for PHP</td><td>I wanted to try using a templating language, and a) preferred the syntax over Twig and b) have vague notions of working with Laravel at some point in the future.</td></tr><tr><td>JavaScript</td><td>Secondary programming language</td><td>Used in the WordPress plugin where necessary in the back-end for custom blocks or customisations of core blocks (anything that can&#39;t be done in PHP), and in the standalone library for client-side interactivity.</td></tr><tr><td>Vue.js</td><td>Front-end JavaScript framework</td><td>Used in select places to provide more advanced interactivity and responsiveness, such as the responsive menu in the <code>SiteHeader</code>. <a href="https://github.com/FranckFreiburger/vue3-sfc-loader" target="_blank" rel="noopener noreferrer">Vue SFC Loader</a> is used to Vue-ify certain components (or parts of them) without the whole thing needing to be a Vue app.</td></tr></tbody></table><h3 id="third-party-plugins" tabindex="-1"><a class="header-anchor" href="#third-party-plugins"><span>Third-party plugins</span></a></h3><table><thead><tr><th>Plugin</th><th>Description</th><th>Details/Rationale</th></tr></thead><tbody><tr><td>BaguetteBox</td><td>Vanilla JS plugin</td><td>Used to enable lightbox functionality in the <code>Gallery</code> component.</td></tr></tbody></table><h2 id="dev-tooling" tabindex="-1"><a class="header-anchor" href="#dev-tooling"><span>Dev Tooling</span></a></h2><table><thead><tr><th>Technology</th><th>Description</th><th>Details/Rationale</th></tr></thead><tbody><tr><td>SCSS</td><td>CSS preprocessor</td><td>Intended to not be essential for consuming projects, but used in Comet Core for improved developer experience for the library&#39;s foundational CSS.</td></tr><tr><td>Rollup</td><td>JavaScript bundler</td><td>Used to bundle the core package&#39;s JavaScript into one file, to make it easier for implementations to use. (I previously had import path issues when trying to use the individual scripts in the WP plugin for example - this solves those.)</td></tr><tr><td>Composer</td><td>PHP package manager</td><td>Used to manage PHP dependencies, and within packages other dependencies that should be uploaded to the server. Dev-only dependencies should be installed at the project root, so that packages&#39; <code>vendor</code> folders contain only production dependencies.</td></tr><tr><td>NPM</td><td>JavaScript package manager</td><td>Used to manage JavaScript dependencies and build scripts. Primarily for local development and tooling (e.g. Storybook, Rollup). <code>node_modules</code> for any package should not be uploaded to the server - any JavaScript dependencies that reside here need to be compiled into production bundles that do get uploaded.</td></tr></tbody></table><h2 id="directory-structure" tabindex="-1"><a class="header-anchor" href="#directory-structure"><span>Directory structure</span></a></h2><table><thead><tr><th>Directory</th><th>Subdirectory</th><th>Purpose</th></tr></thead><tbody><tr><td><code>./docs</code></td><td></td><td>The built documentation site, which is generated from the <code>docs-site</code> directory. <strong>Do not edit the contents of this folder - it will get overwritten the next time the site is built</strong>.</td></tr><tr><td><code>./docs-site</code></td><td></td><td>The source files for this documentation site, which is built with <a href="https://vuepress.vuejs.org/" target="_blank" rel="noopener noreferrer">VuePress</a>.</td></tr><tr><td><code>./packages</code></td><td><code>/core</code></td><td>The core package, which contains the library&#39;s components and styles.</td></tr><tr><td><code>./packages</code></td><td><code>/comet-plugin</code></td><td>The core WordPress plugin, which implements Comet versions of select WordPress core blocks and most other core Comet Components.</td></tr><tr><td><code>./packages</code></td><td><code>/comet-calendar</code></td><td>The WordPress plugin for managing and displaying event information using Comet Components.</td></tr><tr><td><code>./packages</code></td><td><code>/comet-canvas</code></td><td>WordPress parent theme. Implements Comet Components for global layout elements such as the header and footer, and provides theming foundations and hooks for child theme implementations.</td></tr><tr><td><code>./scripts</code></td><td></td><td>Utilities for local development.</td></tr><tr><td><code>./test</code></td><td></td><td>Configuration and utilities for the browser testing environment, unit testing, and integration testing.</td></tr></tbody></table><h2 id="glossary-of-terms" tabindex="-1"><a class="header-anchor" href="#glossary-of-terms"><span>Glossary of terms</span></a></h2><table><thead><tr><th>Term</th><th>Definition</th></tr></thead><tbody><tr><td>WSL</td><td>Windows Subsystem for Linux. This allows you to run a Linux terminal within Windows, which provides a Bash shell as opposed to the Command Prompt or PowerShell.</td></tr><tr><td>NVM</td><td>Node Version Manager. This is a tool that allows you to manage multiple versions of Node.js on your machine.</td></tr><tr><td>NPM</td><td>Node Package Manager. This is a tool that comes with Node and allows you to install and manage JavaScript packages. For context, it&#39;s like Composer for PHP. The main place to find packages you can use with it on <a href="https://www.npmjs.com/" target="_blank" rel="noopener noreferrer">npmjs.com</a>.</td></tr><tr><td>Composer</td><td>A package/dependency manager for PHP. It&#39;s like NPM for PHP, with additional features for things like class loading. The main place to find packages you can use with it is <a href="https://packagist.org/" target="_blank" rel="noopener noreferrer">Packagist</a>.</td></tr><tr><td>Chocolatey, Homebrew, APT</td><td>OS-level package managers that enable you to install and manage things like PHP and Node from the command line. <a href="https://community.chocolatey.org/" target="_blank" rel="noopener noreferrer">Chocolatey</a> is for Windows, <a href="https://brew.sh/" target="_blank" rel="noopener noreferrer">Homebrew</a> is for MacOS, and APT is built into Linux.</td></tr></tbody></table>',11))])}const v=i(u,[["render",p]]),y=JSON.parse('{"path":"/development-core/overview.html","title":"Overview","lang":"en-AU","frontmatter":{"position":0},"headers":[{"level":2,"title":"Tech Stack","slug":"tech-stack","link":"#tech-stack","children":[{"level":3,"title":"Core Technologies","slug":"core-technologies","link":"#core-technologies","children":[]},{"level":3,"title":"Third-party plugins","slug":"third-party-plugins","link":"#third-party-plugins","children":[]}]},{"level":2,"title":"Dev Tooling","slug":"dev-tooling","link":"#dev-tooling","children":[]},{"level":2,"title":"Directory structure","slug":"directory-structure","link":"#directory-structure","children":[]},{"level":2,"title":"Glossary of terms","slug":"glossary-of-terms","link":"#glossary-of-terms","children":[]}],"git":{"updatedTime":1744275231000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":19}],"changelog":[{"hash":"45a77315c51e6532f20bf311c3ac48f46126e436","time":1744275231000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Simplify Storybook/testing setup by removing the need for symlinks"},{"hash":"e6469f64e800c7d9711a2cafcd7934507e735737","time":1744263113000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Refactor(Accordion, Tabs): Have standalone versions use the new Vue component"},{"hash":"7d15f09f18c9133c0dce33d0572fe87f1ab2f50c","time":1743834499000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Add to quick start detail page; improve overall navigation incl. adding in-page sticky menu for some pages"},{"hash":"e1594064395e6943c5e62d2ba3d282aa90c6a0df","time":1743768573000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add search"},{"hash":"9327d3d2a273c6125f395c48ab9acfe1e6b6df85","time":1743766162000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Add quick start dev guide, move detail pages, and simplify sidebar"},{"hash":"4fda7f5158eab97de8674c8ee0e915539ee998b9","time":1743203983000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add PHP architecture overview; update page order and other tweaks"},{"hash":"8b540960a2d2a19bf7530fc92ee7e0b606d5a3cb","time":1742900302000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Various doc tweaks"},{"hash":"1cc0f89c7b4a8f37925f73ae409bfcb3b5592364","time":1742796925000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Config: Enable co-locating test files without them being included in Composer project installations"},{"hash":"bd46ea421c43d7241b0682e551b9810fce24a5c8","time":1742709938000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Finish migrating docs to VuePress"},{"hash":"593c083e86d6a8baa7e78e6af98e148d7f5d69b9","time":1742624410000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"More work on new docs; WIP on updating and improving doc generation for abstract classes/traits/types"},{"hash":"303cb71ac1533272d792db6690210a5dbf5cf23c","time":1742550096000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Start basic VuePress site for Markdown docs"},{"hash":"1fc87c826b15e91f98c1aed9a07147c20872e5fa","time":1741587863000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Update docs after site header / Vue stuff was added"},{"hash":"8857a625081377191c367af2405183fbb9adc744","time":1741481972000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Update notes about tech stack and tooling approach"},{"hash":"016adeeaf46040703a2095670182d15f477e80a2","time":1740286975000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Implement Rollup and bundle component front-end JS for easier use in WP plugin; associated script and docs updates"},{"hash":"ed8c6c656554b12126c0f63a91e7bf26023fbc84","time":1740280806000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Bunch of work on Tabs and SiteHeader; WP: Handle blocks not having a direct Comet counterpart"},{"hash":"66f4659f38d7e4064a08b78627bc03985a542e60","time":1739355412000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Refactor docs into folder and put development docs in their own page"},{"hash":"4ea4f5bf001d0e6b4940b8187d7994750caa3924","time":1739355112000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add some intro notes"},{"hash":"edd601c3cc2d8582c2940aa61e43663b46719f88","time":1738907193000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Some JSON definitions and associated docblock tweaks"},{"hash":"1e34b3698b8d601c1012ce89fb3f5b97e0c856d4","time":1736321291000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Storybook: Start on some theming and docs"}]},"filePathRelative":"development-core/overview.md"}');export{v as comp,y as data};
