# Storybook Tests

This directory contains Playwright tests for the Storybook UI and contents. These tests are designed to ensure that the Storybook components render correctly and behave as expected.

## Running the Tests

To run the Storybook tests, use the following command:

```bash
npm run test:storybook
```

This command uses the Playwright configuration file located at `.storybook/playwright.config.ts`.

## Troubleshooting

If you encounter a "No tests found" error, make sure:

1. The Storybook server is running (`npm run storybook`)
2. You're using the correct configuration file (`.storybook/playwright.config.ts`)
3. The test files match the pattern specified in the configuration (`**/__tests__/*.spec.ts`)
