import{_ as d,c as u,b as e,a as r,e as s,w as i,d as n,r as l,o as m}from"./app-DTpylkEk.js";const p={},g={class:"table-of-contents"},h={class:"hint-container details"},c={class:"hint-container details"};function f(b,t){const o=l("router-link"),a=l("RouteLink");return m(),u("div",null,[t[20]||(t[20]=e("h1",{id:"testing-overview",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#testing-overview"},[e("span",null,"Testing overview")])],-1)),e("nav",g,[e("ul",null,[e("li",null,[s(o,{to:"#manual-testing"},{default:i(()=>t[0]||(t[0]=[n("Manual testing")])),_:1})]),e("li",null,[s(o,{to:"#automated-testing"},{default:i(()=>t[1]||(t[1]=[n("Automated testing")])),_:1}),e("ul",null,[e("li",null,[s(o,{to:"#definitions-and-tooling"},{default:i(()=>t[2]||(t[2]=[n("Definitions and tooling")])),_:1})]),e("li",null,[s(o,{to:"#which-type-of-test-to-use"},{default:i(()=>t[3]||(t[3]=[n("Which type of test to use?")])),_:1})])])])])]),t[21]||(t[21]=e("h2",{id:"manual-testing",tabindex:"-1"},[e("a",{class:"header-anchor",href:"#manual-testing"},[e("span",null,"Manual testing")])],-1)),e("p",null,[t[6]||(t[6]=n("For development purposes, examples of individual component output as well as pages of usage scenarios can be viewed and tested in the browser using the ")),s(a,{to:"/development/testing/browser.html"},{default:i(()=>t[4]||(t[4]=[n("browser testing server")])),_:1}),t[7]||(t[7]=n(". Component output and documentation can also be viewed in ")),s(a,{to:"/development/testing/storybook.html"},{default:i(()=>t[5]||(t[5]=[n("Storybook")])),_:1}),t[8]||(t[8]=n("."))]),t[22]||(t[22]=r('<h2 id="automated-testing" tabindex="-1"><a class="header-anchor" href="#automated-testing"><span>Automated testing</span></a></h2><p>Automated testing helps ensure that the code we write works as expected in given scenarios, and that changes to the code do not introduce new bugs, regressions. It can also serve as documentation for how a component is expected to behave in different scenarios, and can be used as a tool to guide development that ensures all requirements are met (known as <a href="https://tidyfirst.substack.com/p/canon-tdd" target="_blank" rel="noopener noreferrer">test-driven development</a>).</p><h3 id="definitions-and-tooling" tabindex="-1"><a class="header-anchor" href="#definitions-and-tooling"><span>Definitions and tooling</span></a></h3>',3)),e("details",h,[t[12]||(t[12]=e("summary",null,"Unit testing",-1)),t[13]||(t[13]=e("p",null,`Unit testing is the practice of testing the result of a function or method in isolation, without even rendering or viewing them in a browser environment (making them convenient and easy to run - it's as simple as a terminal command or using an IDE that supports your testing framework). Dynamic data is "mocked" or "stubbed" to simulate scenarios we want to test, and the output is compared to the expected result.`,-1)),t[14]||(t[14]=e("p",null,"Unit tests can be thought of as the building blocks of overall test coverage, as they test the smallest pieces of code and ensure they work before they are integrated into more complex use cases. Consequently, they can be instrumental in narrowing down the source of bugs and catching unexpected regressions.",-1)),e("p",null,[t[10]||(t[10]=n("In Comet Components, unit testing is done with ")),s(a,{to:"/development/testing/unit-testing.html"},{default:i(()=>t[9]||(t[9]=[n("Pest")])),_:1}),t[11]||(t[11]=n("."))])]),e("details",c,[t[18]||(t[18]=e("summary",null,"Integration testing",-1)),t[19]||(t[19]=e("p",null,"Integration testing is the practice of testing the interaction between components and their dependencies to ensure they work together as expected. In a front-end context, this often involves rendering combinations of components in a browser environment and testing their behaviour and appearance as a user would see and use them.",-1)),e("p",null,[t[16]||(t[16]=n("In Comet Components, this is done using ")),s(a,{to:"/development/testing/integration-testing.html"},{default:i(()=>t[15]||(t[15]=[n("Playwright")])),_:1}),t[17]||(t[17]=n("."))])]),t[23]||(t[23]=r('<details class="hint-container details"><summary>Accessibility testing</summary><p>Accessibility testing is the practice of testing a component or page to ensure that it meets accessibility guidelines and is usable with assistive technologies. This can include checking for proper semantic HTML, ARIA attributes, colour contrast for text readability, and compatibility with keyboard navigation.</p><p>In Comet Components, accessibility testing is part of both manual and automated integration testing, as it requires rendering of components in a browser environment so they can be analysed in an end user environment.</p></details><details class="hint-container details"><summary>Visual regression testing</summary><p>Visual regression testing is the practice of taking screenshots of a component or page and comparing them to a baseline image to ensure that the visual appearance of a component has not changed unexpectedly.</p><p>VR testing has not yet been implemented in Comet Components, but is planned.</p></details><h3 id="which-type-of-test-to-use" tabindex="-1"><a class="header-anchor" href="#which-type-of-test-to-use"><span>Which type of test to use?</span></a></h3><p>Some examples of when to use each type of test in Comet Components are:</p><table><thead><tr><th>Example requirement</th><th>Test type</th></tr></thead><tbody><tr><td>Given a particular result of a utility function called inside it, the result of a component method is correct</td><td>Unit test</td></tr><tr><td>Given a particular constructor input value, a component&#39;s rendered HTML includes the correct value for an attribute</td><td>Unit test</td></tr><tr><td>In a given component nesting scenario, a component has the correct padding</td><td>Integration test</td></tr><tr><td>When a button is clicked, a modal opens with the correct content</td><td>Integration test</td></tr><tr><td>Given particular attributes, the text in a component renders with accessible contrast</td><td>Integration test</td></tr></tbody></table>',5))])}const w=d(p,[["render",f]]),y=JSON.parse('{"path":"/development/testing/overview.html","title":"Overview","lang":"en-AU","frontmatter":{"title":"Overview","position":0},"headers":[{"level":2,"title":"Manual testing","slug":"manual-testing","link":"#manual-testing","children":[]},{"level":2,"title":"Automated testing","slug":"automated-testing","link":"#automated-testing","children":[{"level":3,"title":"Definitions and tooling","slug":"definitions-and-tooling","link":"#definitions-and-tooling","children":[]},{"level":3,"title":"Which type of test to use?","slug":"which-type-of-test-to-use","link":"#which-type-of-test-to-use","children":[]}]}],"git":{"updatedTime":1743426237000,"contributors":[{"name":"Leesa Ward","username":"","email":"leesa@doubleedesign.com.au","commits":3}],"changelog":[{"hash":"bc347e2ac045f22432775b602ad0302a58c5033b","time":1743426237000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Test: Add unit test utility for finding elements by class name"},{"hash":"1fc876f65b45e5157e264799d11a4001598c7f08","time":1743327721000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Refactor tests: Migrate unit tests to Pest"},{"hash":"75602a90861239de8b335267f83d0c46af5c31da","time":1743319309000,"email":"leesa@doubleedesign.com.au","author":"Leesa Ward","message":"Docs: Add to testing docs"}]},"filePathRelative":"development/testing/overview.md"}');export{w as comp,y as data};
