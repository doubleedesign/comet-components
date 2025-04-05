import{_ as i,c as d,a as r,b as a,d as o,e as s,w as n,r as l,o as c}from"./app-B-D30-e-.js";const m="/docs/comet.png",u={},h={class:"hint-container details"},b={class:"hint-container details"};function g(p,e){const t=l("RouteLink");return c(),d("div",null,[e[16]||(e[16]=r('<h1 id="welcome" tabindex="-1"><a class="header-anchor" href="#welcome"><span>Welcome!</span></a></h1><figure class="comet-photo"><p><img src="'+m+'" alt="Comet"></p><figcaption class="comet-photo-caption"><strong>This is Comet.</strong>I wanted a short and snappy name for this project, alliteration sounds good for that, and he is a good boy.</figcaption></figure><p>Comet Components is a PHP-driven web UI component library initially developed as an abstraction layer for WordPress blocks, with the intention of being able to use the same components in other projects.</p><p>It is built with PHP using an object-oriented architecture where each component is passed an array of <code>$attributes</code> and in most cases, either a string of <code>$content</code> or an array of component objects called <code>$innerComponents</code>.</p><ul><li><a href="https://storybook.cometcomponents.io" target="_blank" rel="noopener noreferrer">View components in Storybook</a></li><li><a href="https://github.com/doubleedesign/comet-components" target="_blank" rel="noopener noreferrer">View the code on GitHub</a></li></ul><h2 id="guiding-principles" tabindex="-1"><a class="header-anchor" href="#guiding-principles"><span>Guiding principles</span></a></h2>',6)),a("details",h,[e[3]||(e[3]=a("summary",null,"Clean, semantic, accessible HTML",-1)),e[4]||(e[4]=a("p",null,"Semantic HTML is a priority in Comet Components, with a focus on accessibility and enabling easy theming while avoiding class name soup. This is achieved through:",-1)),e[5]||(e[5]=a("ul",null,[a("li",null,[o("ARIA roles and "),a("a",{href:"http://getbem.com/",target:"_blank",rel:"noopener noreferrer"},"BEM"),o(' class naming for "what it is" and "what we call it" labelling in the code, combined with')]),a("li",null,'ARIA attributes where possible and practical for "its current state", and'),a("li",null,'custom data attributes for common "how it looks" properties.')],-1)),e[6]||(e[6]=a("p",null,'If someone hits "view source" on a Comet Components site, I want them to revel in how easy it is to read and understand - while seeing enough selectors to enable custom styling.',-1)),e[7]||(e[7]=a("p",null,[a("small",null,'And by that I really mean: if certain former teachers, mentors, or colleagues of mine were to hit "view source" or take it for a spin with assistive technologies, I want them to feel a rush of clean-code, design-system-mindset, structural perfectionist, and/or accessibility advocate satisfaction. If any of my former students were to do so, they should see me practising what I preach. 😆 ')],-1)),a("p",null,[e[1]||(e[1]=o("The benefits, execution, and caveats of this approach are covered in more detail on the ")),s(t,{to:"/technical-deep-dives/html-css-sass.html"},{default:n(()=>e[0]||(e[0]=[o("HTML, CSS, and SCSS technical deep dive")])),_:1}),e[2]||(e[2]=o(" page."))])]),e[17]||(e[17]=a("details",{class:"hint-container details"},[a("summary",null,"Atomic design and reusability"),a("p",null,"Comet Components provides building blocks that come together to create flexible yet consistent UI design components and patterns. The core library includes both the atomic building blocks and many common patterns."),a("p",null,"This approach allows for easy reuse and extension of components while maintaining consistency. Atomic building blocks also enable shared foundational styling and granular automated testing.")],-1)),a("details",b,[e[13]||(e[13]=a("summary",null,"Flexibility - theming and customisation",-1)),e[14]||(e[14]=a("p",null,"Comet Components is designed to provide sensible defaults to so you can hit the ground running, while also being easy to re-skin by using vanilla CSS variables for core design tokens such as colours, spacing, and typography.",-1)),e[15]||(e[15]=a("p",null,[o("More advanced customisation is enabled through the object-oriented architecture and use of the "),a("a",{href:"https://laravel.com/docs/12.x/frontend#php-and-blade",target:"_blank",rel:"noopener noreferrer"},"Blade"),o(" templating engine.")],-1)),a("p",null,[e[10]||(e[10]=o("More information can be found in the ")),s(t,{to:"/usage/theming.html"},{default:n(()=>e[8]||(e[8]=[o("Theming")])),_:1}),e[11]||(e[11]=o(" and ")),s(t,{to:"/usage/extending.html"},{default:n(()=>e[9]||(e[9]=[o("Extending")])),_:1}),e[12]||(e[12]=o(" documentation."))])])])}const w=i(u,[["render",g]]),y=JSON.parse('{"path":"/intro.html","title":"Introduction","lang":"en-AU","frontmatter":{"title":"Introduction","position":0},"headers":[{"level":2,"title":"Guiding principles","slug":"guiding-principles","link":"#guiding-principles","children":[]}],"git":{"updatedTime":1743751485000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":19}],"changelog":[{"hash":"241d4d69a5f4eb46b1ca8764b5e0d98c3500e41c","time":1743751485000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Small doc tweaks"},{"hash":"57f0a70f7abfe3b8bf541a4db7f08f4f3719ef44","time":1743747510000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Redirect local homepage to docs; set up generate-vue-cli; add announcement banner; tweak homepage"},{"hash":"c792c91e35f07889db6d9effc5cb18aee2dc44f8","time":1743431150000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Update code syntax highlighting; misc doc tweaks"},{"hash":"e918bf058917be304d69e468a11b814ae7504cff","time":1743331328000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: More doc tweaks"},{"hash":"a2519aef7d7ea6c5cda3c66142eba8ed17fcf14a","time":1743250619000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Fix(Docs): Fix unwanted hard wraps; Docs: Add links to intro"},{"hash":"b1c17f88cb79eff67f4fa378b40bcec55ab9b3b9","time":1743247252000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Troubleshoot Xdebug and document it"},{"hash":"4fda7f5158eab97de8674c8ee0e915539ee998b9","time":1743203983000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add PHP architecture overview; update page order and other tweaks"},{"hash":"8b540960a2d2a19bf7530fc92ee7e0b606d5a3cb","time":1742900302000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Various doc tweaks"},{"hash":"bd46ea421c43d7241b0682e551b9810fce24a5c8","time":1742709938000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Finish migrating docs to VuePress"},{"hash":"593c083e86d6a8baa7e78e6af98e148d7f5d69b9","time":1742624410000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"More work on new docs; WIP on updating and improving doc generation for abstract classes/traits/types"},{"hash":"303cb71ac1533272d792db6690210a5dbf5cf23c","time":1742550096000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Start basic VuePress site for Markdown docs"},{"hash":"1fc87c826b15e91f98c1aed9a07147c20872e5fa","time":1741587863000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Update docs after site header / Vue stuff was added"},{"hash":"8857a625081377191c367af2405183fbb9adc744","time":1741481972000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Update notes about tech stack and tooling approach"},{"hash":"016adeeaf46040703a2095670182d15f477e80a2","time":1740286975000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Implement Rollup and bundle component front-end JS for easier use in WP plugin; associated script and docs updates"},{"hash":"ed8c6c656554b12126c0f63a91e7bf26023fbc84","time":1740280806000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Bunch of work on Tabs and SiteHeader; WP: Handle blocks not having a direct Comet counterpart"},{"hash":"66f4659f38d7e4064a08b78627bc03985a542e60","time":1739355412000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Refactor docs into folder and put development docs in their own page"},{"hash":"4ea4f5bf001d0e6b4940b8187d7994750caa3924","time":1739355112000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add some intro notes"},{"hash":"edd601c3cc2d8582c2940aa61e43663b46719f88","time":1738907193000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Some JSON definitions and associated docblock tweaks"},{"hash":"1e34b3698b8d601c1012ce89fb3f5b97e0c856d4","time":1736321291000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Storybook: Start on some theming and docs"}]},"filePathRelative":"intro.md"}');export{w as comp,y as data};
