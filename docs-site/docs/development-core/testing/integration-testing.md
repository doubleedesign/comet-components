---
title: Integration testing (Playwright)
position: 4
---

# Integration testing



[[toc]]

::: warning
// TODO: Fill in more details here
:::

## Prerequisites

- [Node](../tooling/node.md) installed on your machine
- Project Node dependencies installed (`npm install`)
- Playwright browsers installed and available on your machine (Windows users - see [troubleshooting](#troubleshooting) below)
- `BROWSER_TEST_URL` in `.env` in the project root directory matches your [Browser testing server](./browser.md) configuration
   - If you're using the basic PHP web server, it should be `http://localhost:6001`
   - If you're using Laravel Herd, it should be `http://comet-components.test`
- [Browser testing server](./browser.md) running and successfully loading pages

## Troubleshooting

::: details Missing Playwright browsers in Windows
In a separate PowerShell window with admin privileges:

```powershell:no-line-numbers
npx playwright install firefox
```
:::
