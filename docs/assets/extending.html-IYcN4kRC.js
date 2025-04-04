import{_ as i,c as l,b as t,a as r,e as a,w as s,r as c,o as d,d as o}from"./app-DTpylkEk.js";const m={},p={class:"table-of-contents"};function u(g,e){const n=c("router-link");return d(),l("div",null,[e[2]||(e[2]=t("h1",{id:"extending",tabindex:"-1"},[t("a",{class:"header-anchor",href:"#extending"},[t("span",null,"Extending")])],-1)),t("nav",p,[t("ul",null,[t("li",null,[a(n,{to:"#overriding-blade-templates"},{default:s(()=>e[0]||(e[0]=[o("Overriding Blade templates")])),_:1})]),t("li",null,[a(n,{to:"#customising-component-classes"},{default:s(()=>e[1]||(e[1]=[o("Customising component classes")])),_:1})])])]),e[3]||(e[3]=r('<h2 id="overriding-blade-templates" tabindex="-1"><a class="header-anchor" href="#overriding-blade-templates"><span>Overriding Blade templates</span></a></h2><div class="hint-container tip"><p class="hint-container-title">Tips</p><p>The WordPress plugin will automatically look for Blade templates in your theme to override the existing block component&#39;s output. To use this option, your custom template needs to have the same name as the original which will be the component&#39;s name in kebab case (e.g. <code>my-component.blade.php</code>), and be placed directly in a folder named <code>components</code> in your theme&#39;s root directory.</p></div><p>For other usages including custom WordPress blocks, you can override the rendering template for a component by passing a custom Blade template path to the component constructor.</p><div class="hint-container warning"><p class="hint-container-title">Warning</p><p>// TODO examples to come</p></div><div class="hint-container warning"><p class="hint-container-title">Warning</p><p>// TODO generic passing of custom Blade template<br> // Method in Renderable?</p></div><h2 id="customising-component-classes" tabindex="-1"><a class="header-anchor" href="#customising-component-classes"><span>Customising component classes</span></a></h2><p>The core component classes are built in an object-oriented fashion, so you can create your own versions of them while keeping the functionality of the original using inheritance.</p><div class="hint-container warning"><p class="hint-container-title">Warning</p><p>// TODO basic example to come</p></div>',8))])}const b=i(m,[["render",u]]),v=JSON.parse('{"path":"/usage/extending.html","title":"Extending","lang":"en-AU","frontmatter":{"position":2},"headers":[{"level":2,"title":"Overriding Blade templates","slug":"overriding-blade-templates","link":"#overriding-blade-templates","children":[]},{"level":2,"title":"Customising component classes","slug":"customising-component-classes","link":"#customising-component-classes","children":[]}],"git":{"updatedTime":1743203983000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":3}],"changelog":[{"hash":"4fda7f5158eab97de8674c8ee0e915539ee998b9","time":1743203983000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add PHP architecture overview; update page order and other tweaks"},{"hash":"8b540960a2d2a19bf7530fc92ee7e0b606d5a3cb","time":1742900302000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Various doc tweaks"},{"hash":"593c083e86d6a8baa7e78e6af98e148d7f5d69b9","time":1742624410000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"More work on new docs; WIP on updating and improving doc generation for abstract classes/traits/types"}]},"filePathRelative":"usage/extending.md"}');export{b as comp,v as data};
