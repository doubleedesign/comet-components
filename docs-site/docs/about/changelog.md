# Releases

:::info
For fun and gratitude, Comet's releases are named after albums that can somehow be associated with a person, event, or even an offhand comment that influenced either the contents of the release itself, or the author's career as a developer.
:::

[[toc]]

## Current version

### 0.0.2 London Calling (10 April 2025)
This second alpha release is focused on completion and refinement of the initial set of Vue-enhanced components, notably completion of `ResponsivePanels` and refactoring of `Accordion` and `Tabs` to use the same Vue components, ensuring consistency and removing Bootstrap from the project dependencies.

:::details London Calling - the story
This release is named for where I met [Derek Mwanza](https://www.creativeconnections.co.uk/) and he told me about using Vue selectively in his WordPress sites. (Amusingly, this was at a React conference!). While we didn't get a chance to dig into the specifics of
_how_ he does this, I was fascinated by the idea and quickly investigated it further when I next had a project that could benefit from it. This mere seed of an idea has been instrumental in solving some pain points in responsive markup and client-side interactivity for me, and hence in the direction of Comet Components.
:::

---

## Release History

### 0.0.1 Fearless (6 April 2025)

A "soft release" of the "incomplete alpha" version of Comet Components, marked for the day I turned the [GitHub repo](https://github.com/doubleedesign/comet-components) public and published the [Core library on Packagist](https://packagist.org/packages/doubleedesign/comet-components-core), a few days after first publishing the [documentation site](https://cometcomponents.io).

- Components that correspond directly to 21 WordPress core blocks
- Plus other components: Container, Accordion, Tabs, Call-to-Action, SiteHeader, SiteFooter, Menu, FileGroup, LinkGroup
- WordPress plugin integrating the 21 core blocks as well as blocks for the majority of the other components
- WordPress parent theme integrating SiteHeader, SiteFooter, and Menu
- Dev tooling including scripts to generate skeleton code for new components, generate JSON files describing components (which also act as a way to manually confirm that fields are correctly defined/implemented), and generate the skeleton code for stories
- Enhancement of the SiteHeader component using Vue.js
- Some unit and integration tests
- Almost-complete documentation website.

:::details Fearless - the story
[Double-E Design](https://www.doubleedesign.com.au) turned 15 in October 2024, but unlike for its 10th "birthday" (where I did a little photo shoot and splashed "10 years of design and code" all over my website and socials) I didn't do anything to mark it. While it was (and is) still very much an active business, it had taken a backseat to the full-time job at [Atlassian](https://www.atlassian.com/) and on top of the time constraints of juggling both, I didn't feel like I'd earned the right to public self-congratulations for it.

The following month, I had the pleasure of presenting a talk on reusable components at [WordCamp Sydney](https://sydney.wordcamp.org/2024/), where I got many ideas both from feedback on my talk and from some of the other talks. This was also where, by complete chance and with incredibly serendipitous timing, I reconnected with [Murray Chapman](https://www.muzkore.com/), one of my favourite teachers from my long educational journey; the one I credit with teaching me to code - you guessed it - 15 years prior.

So while I solemnly swear I am not a Swiftie, when I was trying to come up with a name related to the number 15, I was also reminded of fellow WordCamp speaker [Sandra Lopez](https://www.sandzstudio.com.au/) saying her advice to people new to the industry is to "be a little fearless" which I vibed with so much that this release pretty much couldn't be named anything else! (For the uninitiated: Taylor Swift's album _Fearless_ contains a song called _Fifteen_).
:::

---

## Roadmap

### 0.1.0 Dreamers Are Waiting
The first beta release of Comet Components, which is expected to include:
- completion of most essential features/improvements/enhancements and documentation marked as TODO
- publishing of the Storybook
- extensive automated test coverage.

:::details Dreamers Are Waiting - the story
Early in my career and studies, I was a [Western Chances](https://westernchances.org.au/) scholarship recipient, and at a workshop they ran I met a young woman by the name of [Sarah Bell](https://mantacreative.agency/) (now Duck) who was working for them at the time. We did a little "introduce yourself and what you got your scholarship for" exercise and when I said I got mine for multimedia, Sarah's eyes lit up and so began an unofficial mentorship that very much shaped my early career.

Sarah was one of the people who encouraged me to start [my business](https://www.doubleedesign.com.au), referred me for my first teaching job (delivering 1-2 day short courses on Adobe software and WordPress), and encouraged me to get my Cert IV so I could side-hustle teach at TAFE (and I totally name-dropped her in an interview 5 years later). Importantly, she was proof that these things were achievable because she was doing them herself. Possibly my first exposure to a "portfolio career" person, her influence and guidance were instrumental in shaping my early career.

This release gets its name from Crowded House, a band Sarah and I both love, and...something about being a dreamer fits in there somewhere!
:::

### 0.2.0 For Those About To Rock, We Salute You

This is the pencilling-in of the development of an integration for [SilverStripe CMS](https://www.silverstripe.org/) and [Elemental](https://github.com/silverstripe/silverstripe-elemental). The version number may change as this release may be pushed back to prioritise completion of the WordPress integration as that's already in use.

:::details For Those About To Rock, We Salute You - the story
The year was 2010, and I was studying my Diploma of IT (Web Development) alongside three new friends known as "the boys from TAFE" (I was one of only two women in my class). My brother hooked me up with a real project to build for one of my final assessment tasks - the first database driven CMS site I ever built myself, for which I used SilverStripe. 

According to my belatedly-populated Setlist.fm profile, I only attended two concerts in 2010 (that I remember, anyway) - Taylor Swift and AC/DC. I attended the latter with my brother, whose cricket club that first SilverStripe site was for. From their discography, I selected _For Those About To Rock, We Salute You_ as a nostalgic nod to that group of us at TAFE who were just starting to rock in the tech world.
:::
