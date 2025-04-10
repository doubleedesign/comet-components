import{_ as m,c as g,b as e,a as u,e as l,w as a,d as t,r as p,o as h}from"./app-kQtp0ML3.js";const c={},b={class:"table-of-contents"},f={class:"hint-container details"};function v(w,s){const r=p("router-link"),d=p("Tabs"),i=p("RouteLink");return h(),g("div",null,[s[55]||(s[55]=e("h1",{id:"creating-a-new-component",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#creating-a-new-component"},[e("span",null,"Creating a new component")])],-1)),e("nav",b,[e("ul",null,[e("li",null,[l(r,{to:"#generate-boilerplate-code"},{default:a(()=>s[0]||(s[0]=[t("Generate boilerplate code")])),_:1})]),e("li",null,[l(r,{to:"#compile-assets"},{default:a(()=>s[1]||(s[1]=[t("Compile assets")])),_:1})]),e("li",null,[l(r,{to:"#generate-documentation"},{default:a(()=>s[2]||(s[2]=[t("Generate documentation")])),_:1})]),e("li",null,[l(r,{to:"#prepare-for-browser-testing"},{default:a(()=>s[3]||(s[3]=[t("Prepare for browser testing")])),_:1})]),e("li",null,[l(r,{to:"#view-in-storybook"},{default:a(()=>s[4]||(s[4]=[t("View in Storybook")])),_:1})]),e("li",null,[l(r,{to:"#further-reading"},{default:a(()=>s[5]||(s[5]=[t("Further reading")])),_:1})])])]),s[56]||(s[56]=u('<h2 id="generate-boilerplate-code" tabindex="-1"><a class="header-anchor" href="#generate-boilerplate-code"><span>Generate boilerplate code</span></a></h2><p>To generate the boilerplate code for a new component, run the following command with <code>example</code> and <code>simple</code> replaced with the desired component name and type. Valid types are <code>simple</code>, <code>complex</code>, and <code>wrapper</code>.</p><p>Note: If using PowerShell <strong>or</strong> WSL with Node aliased to PowerShell, do not use equals signs.</p>',3)),l(d,{id:"15",data:[{id:"WSL (Bash)"},{id:"PowerShell"}],"tab-id":"shell"},{title0:a(({value:n,isActive:o})=>s[6]||(s[6]=[t("WSL (Bash)")])),title1:a(({value:n,isActive:o})=>s[7]||(s[7]=[t("PowerShell")])),tab0:a(({value:n,isActive:o})=>s[8]||(s[8]=[e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[e("span",{class:"token function"},"npm"),t(" run generate component -- "),e("span",{class:"token parameter variable"},"--name"),e("span",{class:"token operator"},"="),t("YourThing "),e("span",{class:"token parameter variable"},"--type"),e("span",{class:"token operator"},"="),t("simple")]),t(`
`),e("span",{class:"line"})])])],-1)])),tab1:a(({value:n,isActive:o})=>s[9]||(s[9]=[e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[t("npm run generate component "),e("span",{class:"token operator"},"--"),t(),e("span",{class:"token operator"},"--"),t("name YourThing "),e("span",{class:"token operator"},"--"),e("span",{class:"token function"},"type"),t(" simple")]),t(`
`),e("span",{class:"line"})])])],-1)])),_:1}),s[57]||(s[57]=e("h2",{id:"compile-assets",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#compile-assets"},[e("span",null,"Compile assets")])],-1)),e("p",null,[s[11]||(s[11]=t("Add the SCSS file to ")),s[12]||(s[12]=e("code",null,"blocks.scss",-1)),s[13]||(s[13]=t(" or ")),s[14]||(s[14]=e("code",null,"template-parts.scss",-1)),s[15]||(s[15]=t(" (depending on what it is) in the WordPress plugin and run SASS to compile those. Alternatively you can set up ")),l(i,{to:"/development-core/tooling/phpstorm.html"},{default:a(()=>s[10]||(s[10]=[t("file watchers in PhpStorm")])),_:1}),s[16]||(s[16]=t(" to automatically compile all the SASS on save."))]),e("details",f,[s[21]||(s[21]=e("summary",null,"SASS terminal commands",-1)),l(d,{id:"30",data:[{id:"WSL (Bash)"},{id:"PowerShell"}],"tab-id":"shell"},{title0:a(({value:n,isActive:o})=>s[17]||(s[17]=[t("WSL (Bash)")])),title1:a(({value:n,isActive:o})=>s[18]||(s[18]=[t("PowerShell")])),tab0:a(({value:n,isActive:o})=>s[19]||(s[19]=[e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[e("span",{class:"token builtin class-name"},"cd"),t(" packages/comet-plugin/src")]),t(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"sass blocks.scss:blocks.css"),t(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"sass template-parts.scss:template-parts.css"),t(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"sass editor.scss:editor.css"),t(`
`),e("span",{class:"line"})])])],-1)])),tab1:a(({value:n,isActive:o})=>s[20]||(s[20]=[e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"cd packages/comet-plugin/src"),t(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[t("sass blocks"),e("span",{class:"token punctuation"},"."),t("scss:blocks"),e("span",{class:"token punctuation"},"."),t("css")]),t(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[t("sass template-parts"),e("span",{class:"token punctuation"},"."),t("scss:template-parts"),e("span",{class:"token punctuation"},"."),t("css")]),t(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[t("sass editor"),e("span",{class:"token punctuation"},"."),t("scss:editor"),e("span",{class:"token punctuation"},"."),t("css")]),t(`
`),e("span",{class:"line"})])])],-1)])),_:1})]),e("p",null,[s[23]||(s[23]=t("If you add a custom front-end JavaScript file, add it to ")),s[24]||(s[24]=e("code",null,"rollup.index.js",-1)),s[25]||(s[25]=t(" in the Core package and run Rollup to update the bundle. (You can set up a ")),l(i,{to:"/development-core/tooling/phpstorm.html"},{default:a(()=>s[22]||(s[22]=[t("file watcher")])),_:1}),s[26]||(s[26]=t(" for this too.)"))]),s[58]||(s[58]=u(`<div class="language-bash" data-highlighter="prismjs" data-ext="sh"><pre><code><span class="line"><span class="token function">npm</span> run build</span>
<span class="line"></span></code></pre></div><h2 id="generate-documentation" tabindex="-1"><a class="header-anchor" href="#generate-documentation"><span>Generate documentation</span></a></h2>`,2)),e("p",null,[s[28]||(s[28]=t("Once you have added fields and docblock comments to a component, generate the JSON definition file and ")),l(i,{to:"/usage/overview.html#tycho-template-syntax"},{default:a(()=>s[27]||(s[27]=[t("Tycho Template syntax")])),_:1}),s[29]||(s[29]=t(" XML definition file:"))]),l(d,{id:"55",data:[{id:"WSL (Bash)"},{id:"PowerShell"}],"tab-id":"shell"},{title0:a(({value:n,isActive:o})=>s[30]||(s[30]=[t("WSL (Bash)")])),title1:a(({value:n,isActive:o})=>s[31]||(s[31]=[t("PowerShell")])),tab0:a(({value:n,isActive:o})=>s[32]||(s[32]=[e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[t("php scripts/generate-json-defs.php "),e("span",{class:"token parameter variable"},"--component"),t(" Example")]),t(`
`),e("span",{class:"line"})])])],-1)])),tab1:a(({value:n,isActive:o})=>s[33]||(s[33]=[e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[t("php scripts/generate-json-defs"),e("span",{class:"token punctuation"},"."),t("php "),e("span",{class:"token operator"},"--"),t("component Example")]),t(`
`),e("span",{class:"line"})])])],-1)])),_:1}),s[59]||(s[59]=e("h2",{id:"prepare-for-browser-testing",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#prepare-for-browser-testing"},[e("span",null,"Prepare for browser testing")])],-1)),e("ol",null,[e("li",null,[s[38]||(s[38]=e("p",null,"Generate the boilerplate code for a browser example page and Storybook story:",-1)),l(d,{id:"71",data:[{id:"WSL (Bash)"},{id:"PowerShell"}],"tab-id":"shell"},{title0:a(({value:n,isActive:o})=>s[34]||(s[34]=[t("WSL (Bash)")])),title1:a(({value:n,isActive:o})=>s[35]||(s[35]=[t("PowerShell")])),tab0:a(({value:n,isActive:o})=>s[36]||(s[36]=[e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[t("php scripts/generate-stories.php "),e("span",{class:"token parameter variable"},"--component"),t(" Example")]),t(`
`),e("span",{class:"line"})])])],-1)])),tab1:a(({value:n,isActive:o})=>s[37]||(s[37]=[e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[t("php scripts/generate-stories"),e("span",{class:"token punctuation"},"."),t("php "),e("span",{class:"token operator"},"--"),t("component Example")]),t(`
`),e("span",{class:"line"})])])],-1)])),_:1})]),s[39]||(s[39]=e("li",null,[e("p",null,[t("Update the generated file in the "),e("code",null,"__tests__"),t(" folder to have suitable demo content and handle "),e("code",null,"$_REQUEST"),t(" parameters if the basic handling generated by the script is not sufficient.")])],-1)),s[40]||(s[40]=e("li",null,[e("p",null,[t("Update the Storybook file in the "),e("code",null,"__tests__"),t(" file to ensure suitable examples are shown.")])],-1))]),s[60]||(s[60]=e("h2",{id:"view-in-storybook",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#view-in-storybook"},[e("span",null,"View in Storybook")])],-1)),e("p",null,[s[42]||(s[42]=t("Run the ")),l(i,{to:"/development-core/testing/browser.html"},{default:a(()=>s[41]||(s[41]=[t("local web server")])),_:1}),s[43]||(s[43]=t(" and Storybook (")),s[44]||(s[44]=e("code",null,"npm run storybook",-1)),s[45]||(s[45]=t(" from the project root) to view the new component in the browser."))]),s[61]||(s[61]=e("h2",{id:"further-reading",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#further-reading"},[e("span",null,"Further reading")])],-1)),e("ul",null,[e("li",null,[s[47]||(s[47]=t("See the ")),l(i,{to:"/technical-deep-dives/php-architecture/traits.html"},{default:a(()=>s[46]||(s[46]=[t("PHP Architecture")])),_:1}),s[48]||(s[48]=t(" section for details on the abstract classes you can use as a base for your component, the traits you can use to handle attributes that are used by multiple components in a consistent way, and some data types you can use for attributes."))]),e("li",null,[s[50]||(s[50]=t("See the ")),l(i,{to:"/technical-deep-dives/js-architecture/javascript.html"},{default:a(()=>s[49]||(s[49]=[t("Basic JavaScript")])),_:1}),s[51]||(s[51]=t(" page for details on how to use vanilla JavaScript to add simple client-side interactivity to your components."))]),e("li",null,[s[53]||(s[53]=t("See the ")),l(i,{to:"/technical-deep-dives/js-architecture/vue.html"},{default:a(()=>s[52]||(s[52]=[t("JavaScript Advanced - Vue.js")])),_:1}),s[54]||(s[54]=t(" page for details on how to selectively use Vue.js for a component for more advanced client-side interactivity and reactivity."))])])])}const S=m(c,[["render",v]]),y=JSON.parse('{"path":"/development-core/new-component.html","title":"Creating a new component","lang":"en-AU","frontmatter":{"position":2},"headers":[{"level":2,"title":"Generate boilerplate code","slug":"generate-boilerplate-code","link":"#generate-boilerplate-code","children":[]},{"level":2,"title":"Compile assets","slug":"compile-assets","link":"#compile-assets","children":[]},{"level":2,"title":"Generate documentation","slug":"generate-documentation","link":"#generate-documentation","children":[]},{"level":2,"title":"Prepare for browser testing","slug":"prepare-for-browser-testing","link":"#prepare-for-browser-testing","children":[]},{"level":2,"title":"View in Storybook","slug":"view-in-storybook","link":"#view-in-storybook","children":[]},{"level":2,"title":"Further reading","slug":"further-reading","link":"#further-reading","children":[]}],"git":{"updatedTime":1744275231000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":8}],"changelog":[{"hash":"45a77315c51e6532f20bf311c3ac48f46126e436","time":1744275231000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Simplify Storybook/testing setup by removing the need for symlinks"},{"hash":"b9a8bb46ec370167c4c4711fe4653b09e411c060","time":1744024907000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Add bundling of CSS; Docs: complete the New Implementations page"},{"hash":"7d15f09f18c9133c0dce33d0572fe87f1ab2f50c","time":1743834499000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Add to quick start detail page; improve overall navigation incl. adding in-page sticky menu for some pages"},{"hash":"a2519aef7d7ea6c5cda3c66142eba8ed17fcf14a","time":1743250619000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Fix(Docs): Fix unwanted hard wraps; Docs: Add links to intro"},{"hash":"4fda7f5158eab97de8674c8ee0e915539ee998b9","time":1743203983000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add PHP architecture overview; update page order and other tweaks"},{"hash":"529799e6963158623d0a136bbe3145ebbbaad07e","time":1743165091000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Feature: Generate XML definition for Tycho syntax; update JSON defs"},{"hash":"8b540960a2d2a19bf7530fc92ee7e0b606d5a3cb","time":1742900302000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Various doc tweaks"},{"hash":"593c083e86d6a8baa7e78e6af98e148d7f5d69b9","time":1742624410000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"More work on new docs; WIP on updating and improving doc generation for abstract classes/traits/types"}]},"filePathRelative":"development-core/new-component.md"}');export{S as comp,y as data};
