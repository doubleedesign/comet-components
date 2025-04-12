import{_ as d,c as p,b as t,a as c,d as s,e as n,w as o,r,o as u}from"./app-CO_8o7uO.js";const m={},h={class:"table-of-contents"},g={id:"option-1-laravel-herd",tabindex:"-1"},b={class:"header-anchor",href:"#option-1-laravel-herd"};function v(f,e){const i=r("RouteLink"),l=r("Badge"),a=r("router-link");return u(),p("div",null,[e[14]||(e[14]=t("h1",{id:"browser-testing",tabindex:"-1"},[t("a",{class:"header-anchor",href:"#browser-testing"},[t("span",null,"Browser testing")])],-1)),t("p",null,[e[2]||(e[2]=s("In order to view the component test out put and pages found in each component's ")),e[3]||(e[3]=t("code",null,"__tests__",-1)),e[4]||(e[4]=s(" directory for development and manual testing purposes, view them in ")),n(i,{to:"/development-core/testing/storybook.html"},{default:o(()=>e[0]||(e[0]=[s("Storybook")])),_:1}),e[5]||(e[5]=s(", and run the ")),n(i,{to:"/development-core/testing/integration-tests.html"},{default:o(()=>e[1]||(e[1]=[s("integration tests")])),_:1}),e[6]||(e[6]=s(", you need to run a local web server that can access the test component output files."))]),t("nav",h,[t("ul",null,[t("li",null,[n(a,{to:"#option-1-laravel-herd"},{default:o(()=>[e[7]||(e[7]=s("Option 1: Laravel Herd ")),n(l,{text:"Recommended",vertical:"middle",type:"tip"})]),_:1})]),t("li",null,[n(a,{to:"#option-2-basic-php-web-server"},{default:o(()=>e[8]||(e[8]=[s("Option 2: Basic PHP web server")])),_:1})]),t("li",null,[n(a,{to:"#option-3-phpstorm-s-built-in-web-server"},{default:o(()=>e[9]||(e[9]=[s("Option 3: PhpStorm's built-in web server")])),_:1})])])]),t("h2",g,[t("a",b,[t("span",null,[e[10]||(e[10]=s("Option 1: Laravel Herd ")),n(l,{text:"Recommended",vertical:"middle",type:"tip"})])])]),e[15]||(e[15]=t("p",null,[t("a",{href:"https://herd.laravel.com",target:"_blank",rel:"noopener noreferrer"},"Laravel Herd"),s(" is a local development environment for PHP applications. It provides a simple way to run a local web server with advanced debugging features.")],-1)),t("p",null,[e[12]||(e[12]=s("More details on installing and configuring Herd can be found on the ")),n(i,{to:"/development-core/tooling/php.html"},{default:o(()=>e[11]||(e[11]=[s("PHP")])),_:1}),e[13]||(e[13]=s(" page. The below instructions assume you have Herd installed and running."))]),e[16]||(e[16]=c(`<ol><li>Add Comet Components as a Herd site by either:<br> a. opening the Herd app and clicking on the <strong>Add Site</strong> button. Select the project root directory so that the URL will be <code>http://comet-components.test</code>.<br> b. running <code>herd link comet-components</code> from the project root directory in PowerShell.</li><li>Open the <code>php.ini</code> file for the currently active PHP version in Herd. You can find this in <code>Herd &gt; PHP &gt; right-click on the current version &gt; Open php.ini directory</code>. Add the below lines, filling in your own username and updating the project path as needed:</li></ol><div class="language-ini line-numbers-mode" data-highlighter="prismjs" data-ext="ini"><pre><code><span class="line"><span class="token key attr-name">herd_auto_prepend_file</span> <span class="token punctuation">=</span> <span class="token value attr-value">C:/Users/YOUR_USERNAME/PHPStormProjects/comet-components/test/browser/wrapper-open.php</span></span>
<span class="line"><span class="token key attr-name">herd_auto_append_file</span> <span class="token punctuation">=</span> <span class="token value attr-value">C:/Users/YOUR_USERNAME/PHPStormProjects/comet-components/test/browser/wrapper-close.php</span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div><div class="line-number"></div></div></div><ol start="3"><li>Enable HTTPS for the local site by doing one of the following:</li></ol><ul><li>open the Herd GUI &gt; Dashboard &gt; Open Sites &gt; Comet Compoenents, and tick the &quot;Enable HTTPS&quot; checkbox</li><li>in PowerShell, run <code>herd secure</code> from the project root directory.</li></ul><ol start="4"><li>Restart the PHP service in Herd so that the updated configuration is loaded.</li></ol><p>An example of a testing page URL for this setup is:</p><div class="language-text line-numbers-mode" data-highlighter="prismjs" data-ext="text"><pre><code><span class="line">https://comet-components.test/packages/core/src/components/Columns/__tests__/pages/columns-colours.php\`.</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><h2 id="option-2-basic-php-web-server" tabindex="-1"><a class="header-anchor" href="#option-2-basic-php-web-server"><span>Option 2: Basic PHP web server</span></a></h2><p>Run the following command in the project root directory to start a basic PHP web server:</p><div class="language-bash line-numbers-mode" data-highlighter="prismjs" data-ext="sh"><pre><code><span class="line">php ./test/browser/start.php</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><p>This will start a PHP web server on port 6001. For maximum compatibility with default local dev configurations and path resolution workarounds, it is recommended to:</p><ul><li>add a hosts file entry so you can access this at <a href="https://comet-components.test" target="_blank" rel="noopener noreferrer">https://comet-components.test</a></li><li>generate a local self-signed SSL certificate and add it to your system&#39;s trusted root certificate store.</li></ul><p>An example of a testing page URL with the hosts file entry and certificate is:</p><div class="language-text line-numbers-mode" data-highlighter="prismjs" data-ext="text"><pre><code><span class="line">https://comet-components.test/packages/core/src/components/Columns/__tests__/pages/columns-colours.php</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><p>An example of a testing page URL without the hosts file entry is:</p><div class="language-text line-numbers-mode" data-highlighter="prismjs" data-ext="text"><pre><code><span class="line">http://localhost:6001/packages/core/src/components/Columns/__tests__/pages/columns-colours.php</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><h2 id="option-3-phpstorm-s-built-in-web-server" tabindex="-1"><a class="header-anchor" href="#option-3-phpstorm-s-built-in-web-server"><span>Option 3: PhpStorm&#39;s built-in web server</span></a></h2><p>If you are using PhpStorm, you can also run the built-in web server. This is what you see when you click on the &quot;built in preview&quot; or browser icons that appear in the top right of the editor when you open a file, or right-click on the tab or the file in the Project tool window and navigate to <code>Open in &gt; Browser</code>.</p><p>For this to work, the <code>php.ini</code> configuration file for PhpStorm&#39;s currently selected PHP interpreter must have the <code>auto_prepend_file</code> and <code>auto_append_file</code> values set. For Laravel Herd, follow the instructions above; for other installations, it&#39;s the same just without <code>herd_</code> at the beginning.</p>`,19))])}const y=d(m,[["render",v]]),P=JSON.parse(`{"path":"/development-core/testing/browser.html","title":"Browser testing","lang":"en-AU","frontmatter":{"title":"Browser testing","position":2},"headers":[{"level":2,"title":"Option 1: Laravel Herd","slug":"option-1-laravel-herd","link":"#option-1-laravel-herd","children":[]},{"level":2,"title":"Option 2: Basic PHP web server","slug":"option-2-basic-php-web-server","link":"#option-2-basic-php-web-server","children":[]},{"level":2,"title":"Option 3: PhpStorm's built-in web server","slug":"option-3-phpstorm-s-built-in-web-server","link":"#option-3-phpstorm-s-built-in-web-server","children":[]}],"git":{"updatedTime":1744275231000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":5}],"changelog":[{"hash":"45a77315c51e6532f20bf311c3ac48f46126e436","time":1744275231000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Simplify Storybook/testing setup by removing the need for symlinks"},{"hash":"7d15f09f18c9133c0dce33d0572fe87f1ab2f50c","time":1743834499000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Add to quick start detail page; improve overall navigation incl. adding in-page sticky menu for some pages"},{"hash":"9327d3d2a273c6125f395c48ab9acfe1e6b6df85","time":1743766162000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Add quick start dev guide, move detail pages, and simplify sidebar"},{"hash":"75602a90861239de8b335267f83d0c46af5c31da","time":1743319309000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add to testing docs"},{"hash":"c57f08f4073ef4a64aa9bc79d49a375d4a86a775","time":1743310427000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Tooling: Enable using Herd for viewing test pages and using its Dumps for debugging"}]},"filePathRelative":"development-core/testing/browser.md"}`);export{y as comp,P as data};
