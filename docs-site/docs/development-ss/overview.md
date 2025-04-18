---
title: Overview
position: 0
---

# SilverStripe CMS integration development

:::danger Not yet available
The SilverStripe integration for Comet Components is not yet available. It is on the [roadmap](../about/changelog.md) and these docs are being written in tandem with the development of the integration.
:::

:::important
The SilverStripe CMS integration is being developed for SilverStripe CMS version 6.
:::

:::info
This section of the docs covers developing the SilverStripe CMS Elemental integration for Comet Components itself.

For guidance on implementing Comet Components in your own SilverStripe CMS projects beyond what the official Comet integration provides, see the [SilverStripe usage](../usage/silverstripe.md) section.
:::

[[toc]]

## Prerequisites
- Local SilverStripe CMS installation to use for testing
- [Elemental](https://docs.silverstripe.org/en/6/optional_features/elemental/basic_setup/) installed and configured in your SilverStripe test site
- [Local dev setup](../development-core/setup.md) for the Comet Components project as a whole.

:::tip
Turning on HTTPS for the test site in Laravel Herd will automatically create an Nginx config file in the following location. You can add any additional configuration required for SilverStripe to this file.

```text:no-line-numbers
C:\Users\YOUR_USERNAME\.config\herd\config\valet\Nginx\your-site.test.conf
```
:::

## Recommendations
If using Laravel Herd Pro to run the local site, it is recommended to have Symfony VarDumper installed as a dev dependency in your SilverStripe project so you can use `dump()` for debugging and have your output appear in the Dumps window in Herd.

## Troubleshooting

:::details Changes to theme/config not taking effect
Flush the cache:
```powershell:no-line-numbers
vendor/bin/sake dev/build flush=all
```
:::

:::details "Parse error" when trying to do things in the CMS
If you have `herd_auto_prepend_file` and `herd_auto_prepend_file` set in your `php.ini` file for core library development, comment them out. This is because this config file affects all sites using the same PHP version.
:::
