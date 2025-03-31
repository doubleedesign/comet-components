---
title: Overview
position: 0
---

# Testing overview

[[toc]]

## Manual testing

For development purposes, examples of individual component output as well as pages of usage scenarios can be viewed and tested in the browser using the [browser testing server](./browser.md). Component output and documentation can also be viewed in [Storybook](./storybook.md).

## Automated testing

Automated testing helps ensure that the code we write works as expected in given scenarios, and that changes to the code do not introduce new bugs, regressions. It can also serve as documentation for how a component is expected to behave in different scenarios, and can be used as a tool to guide development that ensures all requirements are met (known as [test-driven development](https://tidyfirst.substack.com/p/canon-tdd)).

### Definitions and tooling
:::details Unit testing
Unit testing is the practice of testing the result of a function or method in isolation, without even rendering or viewing them in a browser environment (making them convenient and easy to run - it's as simple as a terminal command or using an IDE that supports your testing framework). Dynamic data is "mocked" or "stubbed" to simulate scenarios we want to test, and the output is compared to the expected result. 

Unit tests can be thought of as the building blocks of overall test coverage, as they test the smallest pieces of code and ensure they work before they are integrated into more complex use cases. Consequently, they can be instrumental in narrowing down the source of bugs and catching unexpected regressions.

In Comet Components, unit testing is done with [Pest](./unit-testing.md).
:::

:::details Integration testing
Integration testing is the practice of testing the interaction between components and their dependencies to ensure they work together as expected. In a front-end context, this often involves rendering combinations of components in a browser environment and testing their behaviour and appearance as a user would see and use them.

In Comet Components, this is done using [Playwright](./integration-testing.md).
:::

:::details Accessibility testing
Accessibility testing is the practice of testing a component or page to ensure that it meets accessibility guidelines and is usable with assistive technologies. This can include checking for proper semantic HTML, ARIA attributes, colour contrast for text readability, and compatibility with keyboard navigation.

In Comet Components, accessibility testing is part of both manual and automated integration testing, as it requires rendering of components in a browser environment so they can be analysed in an end user environment.
:::

:::details Visual regression testing
Visual regression testing is the practice of taking screenshots of a component or page and comparing them to a baseline image to ensure that the visual appearance of a component has not changed unexpectedly.

VR testing has not yet been implemented in Comet Components, but is planned.
:::

### Which type of test to use?
Some examples of when to use each type of test in Comet Components are:

| Example requirement                                                                                                 | Test type        | 
|---------------------------------------------------------------------------------------------------------------------|------------------|
| Given a particular result of a utility function called inside it, the result of a component method is correct       | Unit test        |
| Given a particular constructor input value, a component's rendered HTML includes the correct value for an attribute | Unit test        |
| In a given component nesting scenario, a component has the correct padding                                          | Integration test |
| When a button is clicked, a modal opens with the correct content                                                    | Integration test |
| Given particular attributes, the text in a component renders with accessible contrast                               | Integration test |

