import{_ as d}from"./phpstorm-pest-nfB0u9FX.js";import{_ as p,c as u,b as t,a as o,d as n,e as s,w as a,r,o as h}from"./app-DTpylkEk.js";const c="/docs/phpstorm-php-bat.png",g={},m={class:"hint-container important"},f={class:"table-of-contents"},b={class:"hint-container details"};function v(w,e){const l=r("RouteLink"),i=r("router-link");return h(),u("div",null,[e[32]||(e[32]=t("h1",{id:"local-development-setup",tabindex:"-1"},[t("a",{class:"header-anchor",href:"#local-development-setup"},[t("span",null,"Local development setup")])],-1)),t("div",m,[e[3]||(e[3]=t("p",{class:"hint-container-title"},"Important",-1)),e[4]||(e[4]=t("p",null,`This is the "decisions, not options" quick start guide which reflects the author's preferred setup, summarised here in the hope that it simplifies getting started on this multi-language project in the sea of options available.`,-1)),t("p",null,[e[1]||(e[1]=n("Details on other options can be found in the ")),s(l,{to:"/local-dev-deep-dives/setup.html"},{default:a(()=>e[0]||(e[0]=[n("Local Dev Deep Dives")])),_:1}),e[2]||(e[2]=n(" section."))])]),e[33]||(e[33]=t("div",{class:"hint-container warning"},[t("p",{class:"hint-container-title"},"Warning"),t("p",null,"This guide is written for Windows users. MacOS and Linux users will need to adapt some steps.")],-1)),t("nav",f,[t("ul",null,[t("li",null,[s(i,{to:"#_0-prerequisites"},{default:a(()=>e[5]||(e[5]=[n("0. Prerequisites")])),_:1})]),t("li",null,[s(i,{to:"#_1-clone-the-repo"},{default:a(()=>e[6]||(e[6]=[n("1. Clone the repo")])),_:1})]),t("li",null,[s(i,{to:"#_2-set-up-laravel-herd"},{default:a(()=>e[7]||(e[7]=[n("2. Set up Laravel Herd")])),_:1})]),t("li",null,[s(i,{to:"#_3-install-sass-globally"},{default:a(()=>e[8]||(e[8]=[n("3. Install Sass globally")])),_:1})]),t("li",null,[s(i,{to:"#_4-install-project-dependencies-and-create-symlinks"},{default:a(()=>e[9]||(e[9]=[n("4. Install project dependencies and create symlinks")])),_:1})]),t("li",null,[s(i,{to:"#_5-set-up-the-ide"},{default:a(()=>e[10]||(e[10]=[n("5. Set up the IDE")])),_:1}),t("ul",null,[t("li",null,[s(i,{to:"#plugins"},{default:a(()=>e[11]||(e[11]=[n("Plugins")])),_:1})]),t("li",null,[s(i,{to:"#php-interpreter-and-xdebug"},{default:a(()=>e[12]||(e[12]=[n("PHP Interpreter and Xdebug")])),_:1})]),t("li",null,[s(i,{to:"#terminal"},{default:a(()=>e[13]||(e[13]=[n("Terminal")])),_:1})]),t("li",null,[s(i,{to:"#node"},{default:a(()=>e[14]||(e[14]=[n("Node")])),_:1})]),t("li",null,[s(i,{to:"#file-watchers"},{default:a(()=>e[15]||(e[15]=[n("File watchers")])),_:1})]),t("li",null,[s(i,{to:"#linting-and-formatting"},{default:a(()=>e[16]||(e[16]=[n("Linting and formatting")])),_:1})]),t("li",null,[s(i,{to:"#pest-for-unit-testing"},{default:a(()=>e[17]||(e[17]=[n("Pest (for unit testing)")])),_:1})])])]),t("li",null,[s(i,{to:"#_6-update-the-php-ini-file"},{default:a(()=>e[18]||(e[18]=[n("6. Update the php.ini file")])),_:1})]),t("li",null,[s(i,{to:"#_7-ensure-test-pages-load"},{default:a(()=>e[19]||(e[19]=[n("7. Ensure test pages load")])),_:1})]),t("li",null,[s(i,{to:"#_8-set-up-playwright-for-integration-testing"},{default:a(()=>e[20]||(e[20]=[n("8. Set up Playwright (for integration testing)")])),_:1})]),t("li",null,[s(i,{to:"#_9-run-storybook"},{default:a(()=>e[21]||(e[21]=[n("9. Run Storybook")])),_:1})])])]),e[34]||(e[34]=o(`<h2 id="_0-prerequisites" tabindex="-1"><a class="header-anchor" href="#_0-prerequisites"><span>0. Prerequisites</span></a></h2><ul><li>Git installed locally</li><li>Windows machine with sufficient privileges to create symbolic links using PowerShell <ul><li><em>or</em> the ability to translate/adapt PowerShell commands and scripts for your OS</li></ul></li><li>Sufficient privileges to install software on your machine.</li></ul><h2 id="_1-clone-the-repo" tabindex="-1"><a class="header-anchor" href="#_1-clone-the-repo"><span>1. Clone the repo</span></a></h2><ol><li>Create a directory in <code>C:\\Users\\&lt;your-username&gt;</code> called <code>PhpStormProjects</code></li><li>Clone the repo using your GUI of choice (such as <a href="https://www.gitkraken.com/" target="_blank" rel="noopener noreferrer">GitKraken</a>) or via your terminal (from the <code>PhpStormProjects</code> directory):</li></ol><div class="language-powershell line-numbers-mode" data-highlighter="prismjs" data-ext="powershell"><pre><code><span class="line">git clone https:<span class="token operator">/</span><span class="token operator">/</span>github<span class="token punctuation">.</span>com/doubleedesign/comet-components<span class="token punctuation">.</span>git</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><ol start="3"><li>Create and check out a branch for the work you&#39;re going to do:</li></ol><div class="language-powershell line-numbers-mode" data-highlighter="prismjs" data-ext="powershell"><pre><code><span class="line">git checkout <span class="token operator">-</span>b &lt;your-branch-name&gt;</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><h2 id="_2-set-up-laravel-herd" tabindex="-1"><a class="header-anchor" href="#_2-set-up-laravel-herd"><span>2. Set up Laravel Herd</span></a></h2><p>Laravel Herd is an all-in-one local development environment tool for PHP and Node. It takes the place of the likes of WAMP, MAMP, or XAMPP for PHP, and Node Version Manager (NVM) for Node, while also providing <a href="https://getcomposer.org/" target="_blank" rel="noopener noreferrer">Composer</a> and <a href="https://xdebug.org/" target="_blank" rel="noopener noreferrer">Xdebug</a> out of the box.</p><ol><li>If you already have Node installed on your machine, remove it so Herd can install the version of NVM it requires and thus manage Node for you. (Other instances of PHP can stay if you want.)</li><li>Download and install <a href="https://herd.laravel.com/" target="_blank" rel="noopener noreferrer">Laravel Herd Pro</a></li></ol><details class="hint-container details"><summary>Do I really need to pay for Pro?</summary><p>No, but without Pro you won&#39;t have built-in Xdebug, the Dumps feature, or database services.</p><ul><li>Xdebug is required for generating unit test coverage reports</li><li>Dumps is a great debugging tool which is referenced in this documentation</li><li>Database services are required if you want to use Herd to develop websites with WordPress or any other database-driven CMS.</li></ul></details><ol start="3"><li>Make sure Herd is controlling PHP:</li></ol><div class="language-powershell line-numbers-mode" data-highlighter="prismjs" data-ext="powershell"><pre><code><span class="line"><span class="token function">Get-Command</span> php</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><p>The output should list the source as <code>C:\\Users\\&lt;your-username&gt;\\.config\\herd\\bin\\php84\\php.exe</code> or similar according to the global PHP version you have set in Herd.</p><details class="hint-container details"><summary>What if my PHP source is not Herd?</summary><p>Go into your system environment variables and move the <code>PATH</code> entry for Herd to the top. Restart PowerShell and try again.</p></details><ol start="4"><li>Make sure Composer is installed and available:</li></ol><div class="language-powershell line-numbers-mode" data-highlighter="prismjs" data-ext="powershell"><pre><code><span class="line"><span class="token function">Get-Command</span> composer</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><p>The output should list the source as <code>C:\\Users\\&lt;your-username&gt;\\.config\\herd\\bin\\composer.bat</code>.</p><ol start="5"><li>Make sure Node is installed and available:</li></ol><div class="language-powershell line-numbers-mode" data-highlighter="prismjs" data-ext="powershell"><pre><code><span class="line"><span class="token function">Get-Command</span> node</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><p>The output should list the source as <code>C:\\Program Files\\nodejs\\node.exe</code> and the version should match the installed version listed in Herd.</p><p>If you install alternative versions in Herd, change the active version with:</p><div class="language-powershell line-numbers-mode" data-highlighter="prismjs" data-ext="powershell"><pre><code><span class="line">nvm use &lt;version&gt;</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><ol start="6"><li>Tell Herd where the project is using one of the following methods:</li></ol><ul><li>In the Herd GUI, go to <code>General</code> in the left menu, and add <code>PHPStormProjects</code> the <code>Herd Paths</code>. This will make Herd pick up all directories in <code>PHPStormProjects</code> as projects.</li><li>From the Herd GUI Dashboard, click <code>Open Sites</code> and in the screen that appears, click <code>Add</code> and select the directory you cloned the repo into.</li><li>From the <code>comet-components</code> directory your terminal, type <code>herd link</code>.</li></ul><ol start="7"><li>Open <a href="http://comet-components.test" target="_blank" rel="noopener noreferrer">http://comet-components.test</a> in your web browser. It should load a local copy of these docs.</li></ol><h2 id="_3-install-sass-globally" tabindex="-1"><a class="header-anchor" href="#_3-install-sass-globally"><span>3. Install Sass globally</span></a></h2><ol><li>Install the <a href="https://chocolatey.org" target="_blank" rel="noopener noreferrer">Chocolatey</a> package manager for Windows if you don&#39;t already have it.</li><li>Install Dart Sass globally:</li></ol><div class="language-powershell line-numbers-mode" data-highlighter="prismjs" data-ext="powershell"><pre><code><span class="line">choco install sass</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><h2 id="_4-install-project-dependencies-and-create-symlinks" tabindex="-1"><a class="header-anchor" href="#_4-install-project-dependencies-and-create-symlinks"><span>4. Install project dependencies and create symlinks</span></a></h2><p>The project contains multiple sub-packages, and uses both <a href="https://getcomposer.org/" target="_blank" rel="noopener noreferrer">Composer</a> and <a href="https://www.npmjs.com/" target="_blank" rel="noopener noreferrer">NPM</a> to manage different types of dependencies. In addition, symbolic links (symlinks) are used to make certain files available in certain locations for easy browser testing.</p><p>A convenience script is provided to install all dependencies in the project root and all <code>packages</code>, and create symlinks for the <code>test</code> directory. You can run it from the project root (<code>comet-components</code> directory) with:</p><div class="language-powershell line-numbers-mode" data-highlighter="prismjs" data-ext="powershell"><pre><code><span class="line">npm run refresh:all</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><div class="hint-container warning"><p class="hint-container-title">Warning</p><p>Some of the underling scripts are PowerShell scripts, which have not been tested in non-Windows environments. PowerShell for MacOS exists, but you may need to adapt the script for it to work; or alternatively replace it with a shell script. The source code of all the scripts can be found in the <code>scripts</code> directory in the project root.</p></div><h2 id="_5-set-up-the-ide" tabindex="-1"><a class="header-anchor" href="#_5-set-up-the-ide"><span>5. Set up the IDE</span></a></h2><p><a href="https://www.jetbrains.com/phpstorm" target="_blank" rel="noopener noreferrer">PhpStorm</a> is a powerful, fully-featured IDE for PHP and JavaScript development and testing. Download and install it.</p><details class="hint-container details"><summary>Do I really have to use PhpStorm? What about VSCode?</summary><p>No, you don&#39;t have to use PhpStorm, but all IDE-specific information in these docs is written for it.</p></details><h3 id="plugins" tabindex="-1"><a class="header-anchor" href="#plugins"><span>Plugins</span></a></h3><p>Open PhpStorm and go to <code>File &gt; Settings &gt; Plugins</code>.</p><details class="hint-container details"><summary>Plugins to install and enable</summary><p>Install and activate the following plugins and any dependencies they have:</p><ul><li><a href="https://plugins.jetbrains.com/plugin/20175-test-automation" target="_blank" rel="noopener noreferrer">Test Automation</a></li><li><a href="https://plugins.jetbrains.com/plugin/7320-php-annotations" target="_blank" rel="noopener noreferrer">PHP Annotations</a></li><li><a href="https://plugins.jetbrains.com/plugin/10249-powershell" target="_blank" rel="noopener noreferrer">PowerShell</a></li></ul><p>In <code>File &gt; Settings &gt; Plugins</code>, ensure the following bundled plugins are enabled:</p><ul><li>PHP</li><li>PHP Architecture</li><li>JavaScript and TypeScript</li><li>Node.js</li><li>Vue.js</li><li>Ini</li><li>JSON</li><li>CSS</li><li>Sass</li><li>Blade</li><li>Pest</li><li>Terminal</li></ul></details><h3 id="php-interpreter-and-xdebug" tabindex="-1"><a class="header-anchor" href="#php-interpreter-and-xdebug"><span>PHP Interpreter and Xdebug</span></a></h3><ol><li>In <code>File &gt; Settings &gt; PHP</code>, click the 3 dot button next to the <code>CLI Interpreter</code> dropdown.</li><li>Add the Path to Herd&#39;s PHP <code>.bat</code> file so that it will use the global PHP version set in Herd.</li><li>Click the refresh button and make sure it has picked up the version, configuration file, and Xdebug correctly. If it does not detect Xdebug, you can manually add the path to it as shown in the below screenshot.</li></ol><p><img src="`+c+'" alt="PHP interpreter settings"></p><h3 id="terminal" tabindex="-1"><a class="header-anchor" href="#terminal"><span>Terminal</span></a></h3><p>Go to <code>File &gt; Settings &gt; Tools &gt; Terminal</code> and:</p><ul><li>in the <code>Shell path</code> field, select or enter the path to PowerShell</li><li>tick <code>Shell integration</code></li><li>tick <code>Add default PHP interpreter to PATH</code> (it should already be there but it can&#39;t hurt to tick it)</li><li>tick <code>Add node_modules/.bin</code> from the project root to PATH.</li></ul>',46)),t("details",b,[e[25]||(e[25]=t("summary",null,"Do I have to use PowerShell?",-1)),t("p",null,[e[23]||(e[23]=n("No. CMD? Amateur, just use PowerShell. WSL? Hardcore, I like it. Instructions for almost all steps using WSL are available in the ")),s(l,{to:"/local-dev-deep-dives/setup.html"},{default:a(()=>e[22]||(e[22]=[n("Local setup deep dives")])),_:1}),e[24]||(e[24]=n(" section. (But be warned: A lot of them just route commands through PowerShell anyway, as integration of all tooling such as the IDE, PHP, Xdebug, Node, and Playwright is easier if you use the native Windows instances of Node, PHP, etc. installed by Herd.)"))])]),e[35]||(e[35]=o('<h3 id="node" tabindex="-1"><a class="header-anchor" href="#node"><span>Node</span></a></h3><p>Enable Node.js support in <code>File &gt; Settings &gt; Languages &amp; Frameworks &gt; Node.js</code>:</p><ul><li>In the <code>Node interpreter</code> dropdown, ensure <code>C:\\Program Files\\nodejs\\node.exe</code> is selected.</li><li>Tick <code>Coding assistance for Node.js</code>.</li></ul><h3 id="file-watchers" tabindex="-1"><a class="header-anchor" href="#file-watchers"><span>File watchers</span></a></h3>',4)),t("p",null,[e[27]||(e[27]=n("Follow the instructions in the ")),s(l,{to:"/local-dev-deep-dives/tooling-guides/phpstorm.html#file-watchers"},{default:a(()=>e[26]||(e[26]=[n("PhpStorm page")])),_:1}),e[28]||(e[28]=n(" to set up file watchers for Sass, Rollup, and the Blade template formatter."))]),e[36]||(e[36]=t("h3",{id:"linting-and-formatting",tabindex:"-1"},[t("a",{class:"header-anchor",href:"#linting-and-formatting"},[t("span",null,"Linting and formatting")])],-1)),t("p",null,[e[30]||(e[30]=n("Configure automatic linting and formatting with a combination of PhpStorm's built-in tools and ESLint as per the instructions in the ")),s(l,{to:"/local-dev-deep-dives/tooling-guides/phpstorm.html#linting-and-formatting"},{default:a(()=>e[29]||(e[29]=[n("PhpStorm page")])),_:1}),e[31]||(e[31]=n("."))]),e[37]||(e[37]=o('<h3 id="pest-for-unit-testing" tabindex="-1"><a class="header-anchor" href="#pest-for-unit-testing"><span>Pest (for unit testing)</span></a></h3><p>Configuration of Pest/PHPUnit is mostly handled in its configuration file (<code>./test/phpunit.xml</code>) and the Run configuration.</p><p>You can check and tweak the default settings for PhpStorm under <code>File &gt; Settings &gt; Languages &amp; Frameworks &gt; PHP &gt; Test Frameworks</code> (shown below) and for the run configuration under <code>Run &gt; Edit Configurations</code>.</p><p><img src="'+d+`" alt="Pest settings"></p><h2 id="_6-update-the-php-ini-file" tabindex="-1"><a class="header-anchor" href="#_6-update-the-php-ini-file"><span>6. Update the <code>php.ini</code> file</span></a></h2><p>Locate the PHP configuration file (as per the PHP interpreter settings above) and add the following lines to it (updated with your username):</p><div class="language-ini line-numbers-mode" data-highlighter="prismjs" data-ext="ini"><pre><code><span class="line"><span class="token key attr-name">herd_auto_prepend_file</span> <span class="token punctuation">=</span> <span class="token value attr-value">C:/Users/YOUR_USERNAME/PHPStormProjects/comet-components/test/browser/wrapper-open.php</span></span>
<span class="line"><span class="token key attr-name">herd_auto_append_file</span> <span class="token punctuation">=</span> <span class="token value attr-value">C:/Users/YOUR_USERNAME/PHPStormProjects/comet-components/test/browser/wrapper-close.php</span></span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div><div class="line-number"></div></div></div><p>This is to wrap test pages with the required opening and closing HTML.</p><h2 id="_7-ensure-test-pages-load" tabindex="-1"><a class="header-anchor" href="#_7-ensure-test-pages-load"><span>7. Ensure test pages load</span></a></h2><p>Navigate to a test page such as <a href="http://comet-components.test/test/browser/pages/container-colours.php" target="_blank" rel="noopener noreferrer">http://comet-components.test/test/browser/pages/container-colours.php</a> in your browser. You should see the page content with styling applied.</p><h2 id="_8-set-up-playwright-for-integration-testing" tabindex="-1"><a class="header-anchor" href="#_8-set-up-playwright-for-integration-testing"><span>8. Set up Playwright (for integration testing)</span></a></h2><p>Playwright itself should be installed in the project by the refresh script listed above, but you may need to install the browsers it uses.</p><ol><li>To install a browser for Playwright, in the location it expects to find it, with the following command:</li></ol><div class="language-powershell line-numbers-mode" data-highlighter="prismjs" data-ext="powershell"><pre><code><span class="line">npx playwright install firefox</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div><ol start="2"><li><p>Ensure the <code>BROWSER_TEST_URL</code> in the <code>.env</code> file in the project root is set to <code>http://comet-components.test</code>.</p></li><li><p>In PhpStorm, ensure it understands Playwright for syntax highlighting and code completion by following these steps:</p></li></ol><ul><li>add Playwright to the JS libraries under <code>Settings &gt; Languages &amp; Frameworks &gt; JavaScript &gt; Libraries</code>. Include all of <code>@playwright/test</code>, <code>playwright</code>, and <code>playwright-core</code>.</li><li>In <code>Settings &gt; Languages &amp; Frameworks &gt; TypeScript</code>, uncheck <code>use types from server</code>.</li></ul><h2 id="_9-run-storybook" tabindex="-1"><a class="header-anchor" href="#_9-run-storybook"><span>9. Run Storybook</span></a></h2><p>The refresh script should have installed the dependencies for Storybook. Run it locally with:</p><div class="language-powershell line-numbers-mode" data-highlighter="prismjs" data-ext="powershell"><pre><code><span class="line">npm run storybook</span>
<span class="line"></span></code></pre><div class="line-numbers" aria-hidden="true" style="counter-reset:line-number 0;"><div class="line-number"></div></div></div>`,19))])}const k=p(g,[["render",v]]),S=JSON.parse('{"path":"/development/setup.html","title":"Local dev quick start","lang":"en-AU","frontmatter":{"title":"Local dev quick start","position":1},"headers":[{"level":2,"title":"0. Prerequisites","slug":"_0-prerequisites","link":"#_0-prerequisites","children":[]},{"level":2,"title":"1. Clone the repo","slug":"_1-clone-the-repo","link":"#_1-clone-the-repo","children":[]},{"level":2,"title":"2. Set up Laravel Herd","slug":"_2-set-up-laravel-herd","link":"#_2-set-up-laravel-herd","children":[]},{"level":2,"title":"3. Install Sass globally","slug":"_3-install-sass-globally","link":"#_3-install-sass-globally","children":[]},{"level":2,"title":"4. Install project dependencies and create symlinks","slug":"_4-install-project-dependencies-and-create-symlinks","link":"#_4-install-project-dependencies-and-create-symlinks","children":[]},{"level":2,"title":"5. Set up the IDE","slug":"_5-set-up-the-ide","link":"#_5-set-up-the-ide","children":[{"level":3,"title":"Plugins","slug":"plugins","link":"#plugins","children":[]},{"level":3,"title":"PHP Interpreter and Xdebug","slug":"php-interpreter-and-xdebug","link":"#php-interpreter-and-xdebug","children":[]},{"level":3,"title":"Terminal","slug":"terminal","link":"#terminal","children":[]},{"level":3,"title":"Node","slug":"node","link":"#node","children":[]},{"level":3,"title":"File watchers","slug":"file-watchers","link":"#file-watchers","children":[]},{"level":3,"title":"Linting and formatting","slug":"linting-and-formatting","link":"#linting-and-formatting","children":[]},{"level":3,"title":"Pest (for unit testing)","slug":"pest-for-unit-testing","link":"#pest-for-unit-testing","children":[]}]},{"level":2,"title":"6. Update the php.ini file","slug":"_6-update-the-php-ini-file","link":"#_6-update-the-php-ini-file","children":[]},{"level":2,"title":"7. Ensure test pages load","slug":"_7-ensure-test-pages-load","link":"#_7-ensure-test-pages-load","children":[]},{"level":2,"title":"8. Set up Playwright (for integration testing)","slug":"_8-set-up-playwright-for-integration-testing","link":"#_8-set-up-playwright-for-integration-testing","children":[]},{"level":2,"title":"9. Run Storybook","slug":"_9-run-storybook","link":"#_9-run-storybook","children":[]}],"git":{"updatedTime":1743766162000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":10}],"changelog":[{"hash":"9327d3d2a273c6125f395c48ab9acfe1e6b6df85","time":1743766162000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Add quick start dev guide, move detail pages, and simplify sidebar"},{"hash":"c792c91e35f07889db6d9effc5cb18aee2dc44f8","time":1743431150000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Update code syntax highlighting; misc doc tweaks"},{"hash":"e918bf058917be304d69e468a11b814ae7504cff","time":1743331328000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: More doc tweaks"},{"hash":"c57f08f4073ef4a64aa9bc79d49a375d4a86a775","time":1743310427000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Tooling: Enable using Herd for viewing test pages and using its Dumps for debugging"},{"hash":"a2519aef7d7ea6c5cda3c66142eba8ed17fcf14a","time":1743250619000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Fix(Docs): Fix unwanted hard wraps; Docs: Add links to intro"},{"hash":"4fda7f5158eab97de8674c8ee0e915539ee998b9","time":1743203983000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add PHP architecture overview; update page order and other tweaks"},{"hash":"8b540960a2d2a19bf7530fc92ee7e0b606d5a3cb","time":1742900302000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Various doc tweaks"},{"hash":"9c76f49464456e960a99f71a3dfa8a3a8ffd612f","time":1742796937000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Test-driven padding improvements"},{"hash":"bd46ea421c43d7241b0682e551b9810fce24a5c8","time":1742709938000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Finish migrating docs to VuePress"},{"hash":"593c083e86d6a8baa7e78e6af98e148d7f5d69b9","time":1742624410000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"More work on new docs; WIP on updating and improving doc generation for abstract classes/traits/types"}]},"filePathRelative":"development/setup.md"}');export{k as comp,S as data};
