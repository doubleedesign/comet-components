---
title: Overview
position: 0
---

# Statamic integration development

:::danger Not yet available
The Statamic integration for Comet Components is not yet available. It is on the [roadmap](../about/changelog.md) and these docs are being written in tandem with the development of the integration.
:::

:::info
This section of the docs covers developing the Statamic starter kit for Comet Components itself.

For guidance on implementing Comet Components in your own Statamic projects beyond what the official Comet integration provides, see the [Statamic usage](../usage/statamic.md) section.
:::

[[toc]]

## Prerequisites
- Local Statamic installation to use for testing
- [Local dev setup](../development-core/setup.md) for the Comet Components project as a whole.

:::tip
Turning on HTTPS for the test site in Laravel Herd will automatically create an Nginx config file in the following location. You can add any additional configuration required for Statamic to this file.

```text:no-line-numbers
C:\Users\YOUR_USERNAME\.config\herd\config\valet\Nginx\your-site.test.conf
```
:::
