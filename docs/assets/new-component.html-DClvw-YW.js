import{_ as m,c as g,b as e,a as u,e as i,w as l,d as a,r as p,o as h}from"./app-DKh57xcl.js";const c={},b={class:"table-of-contents"},v={class:"hint-container details"};function f(w,s){const o=p("router-link"),r=p("Tabs"),d=p("RouteLink");return h(),g("div",null,[s[57]||(s[57]=e("h1",{id:"creating-a-new-component",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#creating-a-new-component"},[e("span",null,"Creating a new component")])],-1)),e("nav",b,[e("ul",null,[e("li",null,[i(o,{to:"#generate-boilerplate-code"},{default:l(()=>s[0]||(s[0]=[a("Generate boilerplate code")])),_:1})]),e("li",null,[i(o,{to:"#compile-assets"},{default:l(()=>s[1]||(s[1]=[a("Compile assets")])),_:1})]),e("li",null,[i(o,{to:"#generate-documentation"},{default:l(()=>s[2]||(s[2]=[a("Generate documentation")])),_:1})]),e("li",null,[i(o,{to:"#prepare-for-browser-testing"},{default:l(()=>s[3]||(s[3]=[a("Prepare for browser testing")])),_:1})]),e("li",null,[i(o,{to:"#view-in-storybook"},{default:l(()=>s[4]||(s[4]=[a("View in Storybook")])),_:1})]),e("li",null,[i(o,{to:"#further-reading"},{default:l(()=>s[5]||(s[5]=[a("Further reading")])),_:1})])])]),s[58]||(s[58]=u('<h2 id="generate-boilerplate-code" tabindex="-1"><a class="header-anchor" href="#generate-boilerplate-code"><span>Generate boilerplate code</span></a></h2><p>To generate the boilerplate code for a new component, run the following command with <code>example</code> and <code>simple</code> replaced with the desired component name and type. Valid types are <code>simple</code>, <code>complex</code>, and <code>wrapper</code>.</p><p>Note: If using PowerShell <strong>or</strong> WSL with Node aliased to PowerShell, do not use equals signs.</p>',3)),i(r,{id:"15",data:[{id:"WSL (Bash)"},{id:"PowerShell"}],"tab-id":"shell"},{title0:l(({value:t,isActive:n})=>s[6]||(s[6]=[a("WSL (Bash)")])),title1:l(({value:t,isActive:n})=>s[7]||(s[7]=[a("PowerShell")])),tab0:l(({value:t,isActive:n})=>s[8]||(s[8]=[e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[e("span",{class:"token function"},"npm"),a(" run generate component -- "),e("span",{class:"token parameter variable"},"--name"),e("span",{class:"token operator"},"="),a("YourThing "),e("span",{class:"token parameter variable"},"--type"),e("span",{class:"token operator"},"="),a("simple")]),a(`
`),e("span",{class:"line"})])])],-1)])),tab1:l(({value:t,isActive:n})=>s[9]||(s[9]=[e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[a("npm run generate component "),e("span",{class:"token operator"},"--"),a(),e("span",{class:"token operator"},"--"),a("name YourThing "),e("span",{class:"token operator"},"--"),e("span",{class:"token function"},"type"),a(" simple")]),a(`
`),e("span",{class:"line"})])])],-1)])),_:1}),s[59]||(s[59]=e("h2",{id:"compile-assets",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#compile-assets"},[e("span",null,"Compile assets")])],-1)),e("p",null,[s[11]||(s[11]=a("Add the SCSS file to ")),s[12]||(s[12]=e("code",null,"blocks.scss",-1)),s[13]||(s[13]=a(" or ")),s[14]||(s[14]=e("code",null,"template-parts.scss",-1)),s[15]||(s[15]=a(" (depending on what it is) in the WordPress plugin and run SASS to compile those. Alternatively you can set up ")),i(d,{to:"/development-core/tooling/phpstorm.html"},{default:l(()=>s[10]||(s[10]=[a("file watchers in PhpStorm")])),_:1}),s[16]||(s[16]=a(" to automatically compile all the SASS on save."))]),e("details",v,[s[21]||(s[21]=e("summary",null,"SASS terminal commands",-1)),i(r,{id:"30",data:[{id:"WSL (Bash)"},{id:"PowerShell"}],"tab-id":"shell"},{title0:l(({value:t,isActive:n})=>s[17]||(s[17]=[a("WSL (Bash)")])),title1:l(({value:t,isActive:n})=>s[18]||(s[18]=[a("PowerShell")])),tab0:l(({value:t,isActive:n})=>s[19]||(s[19]=[e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[e("span",{class:"token builtin class-name"},"cd"),a(" packages/comet-plugin/src")]),a(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"sass blocks.scss:blocks.css"),a(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"sass template-parts.scss:template-parts.css"),a(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"sass editor.scss:editor.css"),a(`
`),e("span",{class:"line"})])])],-1)])),tab1:l(({value:t,isActive:n})=>s[20]||(s[20]=[e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"cd packages/comet-plugin/src"),a(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[a("sass blocks"),e("span",{class:"token punctuation"},"."),a("scss:blocks"),e("span",{class:"token punctuation"},"."),a("css")]),a(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[a("sass template-parts"),e("span",{class:"token punctuation"},"."),a("scss:template-parts"),e("span",{class:"token punctuation"},"."),a("css")]),a(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[a("sass editor"),e("span",{class:"token punctuation"},"."),a("scss:editor"),e("span",{class:"token punctuation"},"."),a("css")]),a(`
`),e("span",{class:"line"})])])],-1)])),_:1})]),e("p",null,[s[23]||(s[23]=a("If you add a custom front-end JavaScript file, add it to ")),s[24]||(s[24]=e("code",null,"rollup.index.js",-1)),s[25]||(s[25]=a(" in the Core package and run Rollup to update the bundle. (You can set up a ")),i(d,{to:"/development-core/tooling/phpstorm.html"},{default:l(()=>s[22]||(s[22]=[a("file watcher")])),_:1}),s[26]||(s[26]=a(" for this too.)"))]),s[60]||(s[60]=u(`<div class="language-bash" data-highlighter="prismjs" data-ext="sh"><pre><code><span class="line"><span class="token function">npm</span> run build</span>
<span class="line"></span></code></pre></div><h2 id="generate-documentation" tabindex="-1"><a class="header-anchor" href="#generate-documentation"><span>Generate documentation</span></a></h2>`,2)),e("p",null,[s[28]||(s[28]=a("Once you have added fields and docblock comments to a component, generate the JSON definition file and ")),i(d,{to:"/usage/overview.html#tycho-template-syntax"},{default:l(()=>s[27]||(s[27]=[a("Tycho Template syntax")])),_:1}),s[29]||(s[29]=a(" XML definition file:"))]),i(r,{id:"55",data:[{id:"WSL (Bash)"},{id:"PowerShell"}],"tab-id":"shell"},{title0:l(({value:t,isActive:n})=>s[30]||(s[30]=[a("WSL (Bash)")])),title1:l(({value:t,isActive:n})=>s[31]||(s[31]=[a("PowerShell")])),tab0:l(({value:t,isActive:n})=>s[32]||(s[32]=[e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[a("php scripts/generate-json-defs.php "),e("span",{class:"token parameter variable"},"--component"),a(" Example")]),a(`
`),e("span",{class:"line"})])])],-1)])),tab1:l(({value:t,isActive:n})=>s[33]||(s[33]=[e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[a("php scripts/generate-json-defs"),e("span",{class:"token punctuation"},"."),a("php "),e("span",{class:"token operator"},"--"),a("component Example")]),a(`
`),e("span",{class:"line"})])])],-1)])),_:1}),s[61]||(s[61]=e("h2",{id:"prepare-for-browser-testing",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#prepare-for-browser-testing"},[e("span",null,"Prepare for browser testing")])],-1)),e("ol",null,[e("li",null,[s[38]||(s[38]=e("p",null,"Generate the boilerplate code for a browser example page and Storybook story:",-1)),i(r,{id:"71",data:[{id:"WSL (Bash)"},{id:"PowerShell"}],"tab-id":"shell"},{title0:l(({value:t,isActive:n})=>s[34]||(s[34]=[a("WSL (Bash)")])),title1:l(({value:t,isActive:n})=>s[35]||(s[35]=[a("PowerShell")])),tab0:l(({value:t,isActive:n})=>s[36]||(s[36]=[e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[a("php scripts/generate-stories.php "),e("span",{class:"token parameter variable"},"--component"),a(" Example")]),a(`
`),e("span",{class:"line"})])])],-1)])),tab1:l(({value:t,isActive:n})=>s[37]||(s[37]=[e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[a("php scripts/generate-stories"),e("span",{class:"token punctuation"},"."),a("php "),e("span",{class:"token operator"},"--"),a("component Example")]),a(`
`),e("span",{class:"line"})])])],-1)])),_:1})]),e("li",null,[s[41]||(s[41]=e("p",null,"Update the symlinks so the local web server/Storybook can access the CSS and JS files:",-1)),i(r,{id:"84",data:[{id:"PowerShell"}],"tab-id":"shell"},{title0:l(({value:t,isActive:n})=>s[39]||(s[39]=[a("PowerShell")])),tab0:l(({value:t,isActive:n})=>s[40]||(s[40]=[e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"npm run refresh:symlinks"),a(`
`),e("span",{class:"line"})])])],-1)])),_:1})]),s[42]||(s[42]=e("li",null,[e("p",null,[a("Update the generated file in "),e("code",null,"./test/browser/components"),a(" to have suitable demo content and handle "),e("code",null,"$_REQUEST"),a(" parameters if the basic handling generated by the script is not sufficient.")])],-1)),s[43]||(s[43]=e("li",null,[e("p",null,[a("Update the Storybook file in "),e("code",null,"./test/browser/stories"),a(" to ensure suitable examples are shown.")])],-1))]),s[62]||(s[62]=e("h2",{id:"view-in-storybook",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#view-in-storybook"},[e("span",null,"View in Storybook")])],-1)),s[63]||(s[63]=e("p",null,"Run the local web server and Storybook (in two separate terminal tabs) to view the new component in the browser:",-1)),i(r,{id:"107",data:[{id:"WSL (Bash)"},{id:"PowerShell"}],"tab-id":"shell"},{title0:l(({value:t,isActive:n})=>s[44]||(s[44]=[a("WSL (Bash)")])),title1:l(({value:t,isActive:n})=>s[45]||(s[45]=[a("PowerShell")])),tab0:l(({value:t,isActive:n})=>s[46]||(s[46]=[e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[e("span",{class:"token function"},"npm"),a(" run test:server")]),a(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-bash","data-highlighter":"prismjs","data-ext":"sh"},[e("pre",null,[e("code",null,[e("span",{class:"line"},[e("span",{class:"token function"},"npm"),a(" run test:storybook")]),a(`
`),e("span",{class:"line"})])])],-1)])),tab1:l(({value:t,isActive:n})=>s[47]||(s[47]=[e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"npm run test:server"),a(`
`),e("span",{class:"line"})])])],-1),e("div",{class:"language-powershell","data-highlighter":"prismjs","data-ext":"powershell"},[e("pre",null,[e("code",null,[e("span",{class:"line"},"npm run test:storybook"),a(`
`),e("span",{class:"line"})])])],-1)])),_:1}),s[64]||(s[64]=e("h2",{id:"further-reading",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#further-reading"},[e("span",null,"Further reading")])],-1)),e("ul",null,[e("li",null,[s[49]||(s[49]=a("See the ")),i(d,{to:"/technical-deep-dives/php-architecture/traits.html"},{default:l(()=>s[48]||(s[48]=[a("PHP Architecture")])),_:1}),s[50]||(s[50]=a(" section for details on the abstract classes you can use as a base for your component, the traits you can use to handle attributes that are used by multiple components in a consistent way, and some data types you can use for attributes."))]),e("li",null,[s[52]||(s[52]=a("See the ")),i(d,{to:"/technical-deep-dives/js-architecture/javascript.html"},{default:l(()=>s[51]||(s[51]=[a("Basic JavaScript")])),_:1}),s[53]||(s[53]=a(" page for details on how to use vanilla JavaScript to add simple client-side interactivity to your components."))]),e("li",null,[s[55]||(s[55]=a("See the ")),i(d,{to:"/technical-deep-dives/js-architecture/vue.html"},{default:l(()=>s[54]||(s[54]=[a("JavaScript Advanced - Vue.js")])),_:1}),s[56]||(s[56]=a(" page for details on how to selectively use Vue.js for a component for more advanced client-side interactivity and reactivity."))])])])}const S=m(c,[["render",f]]),y=JSON.parse('{"path":"/development-core/new-component.html","title":"Creating a new component","lang":"en-AU","frontmatter":{"position":2},"headers":[{"level":2,"title":"Generate boilerplate code","slug":"generate-boilerplate-code","link":"#generate-boilerplate-code","children":[]},{"level":2,"title":"Compile assets","slug":"compile-assets","link":"#compile-assets","children":[]},{"level":2,"title":"Generate documentation","slug":"generate-documentation","link":"#generate-documentation","children":[]},{"level":2,"title":"Prepare for browser testing","slug":"prepare-for-browser-testing","link":"#prepare-for-browser-testing","children":[]},{"level":2,"title":"View in Storybook","slug":"view-in-storybook","link":"#view-in-storybook","children":[]},{"level":2,"title":"Further reading","slug":"further-reading","link":"#further-reading","children":[]}],"git":{"updatedTime":1744024907000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":7}],"changelog":[{"hash":"b9a8bb46ec370167c4c4711fe4653b09e411c060","time":1744024907000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Add bundling of CSS; Docs: complete the New Implementations page"},{"hash":"7d15f09f18c9133c0dce33d0572fe87f1ab2f50c","time":1743834499000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Add to quick start detail page; improve overall navigation incl. adding in-page sticky menu for some pages"},{"hash":"a2519aef7d7ea6c5cda3c66142eba8ed17fcf14a","time":1743250619000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Fix(Docs): Fix unwanted hard wraps; Docs: Add links to intro"},{"hash":"4fda7f5158eab97de8674c8ee0e915539ee998b9","time":1743203983000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add PHP architecture overview; update page order and other tweaks"},{"hash":"529799e6963158623d0a136bbe3145ebbbaad07e","time":1743165091000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Feature: Generate XML definition for Tycho syntax; update JSON defs"},{"hash":"8b540960a2d2a19bf7530fc92ee7e0b606d5a3cb","time":1742900302000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Various doc tweaks"},{"hash":"593c083e86d6a8baa7e78e6af98e148d7f5d69b9","time":1742624410000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"More work on new docs; WIP on updating and improving doc generation for abstract classes/traits/types"}]},"filePathRelative":"development-core/new-component.md"}');export{S as comp,y as data};
