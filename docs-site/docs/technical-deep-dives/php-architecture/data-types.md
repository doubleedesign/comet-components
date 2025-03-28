---
title: Data types
position: 3
---

# Data Types
PHP enums are used to specify valid values for properties. This provides a central location for validation logic and documentation, as well as reducing duplication, ensuring consistency, and enabling type safety.

<div class="data-type-doc">
<div>

## Alignment

Enum used to specify valid values for alignment properties.

::: details Supported values

| Case                      | Value                     |
|---------------------------|---------------------------|
| <code>START</code>        | <code>start</code>        |
| <code>END</code>          | <code>end</code>          |
| <code>CENTER</code>       | <code>center</code>       |
| <code>JUSTIFY</code>      | <code>justify</code>      |
| <code>MATCH_PARENT</code> | <code>match-parent</code> |

:::
### Methods
<dl>
<dt><code>fromString(string $value)</code></dt>
<dd>
Converts a string to the corresponding enum case.

::: details Supported input mappings

| Input        | Result               |
|--------------|----------------------|
| `'start'`    | `self::START`        |
| ` 'left'`    | `self::START`        |
| ` 'top' `    | `self::START`        |
| `'end'`      | `self::END`          |
| ` 'right'`   | `self::END`          |
| ` 'bottom' ` | `self::END`          |
| `'center' `  | `self::CENTER`       |
| `'justify' ` | `self::JUSTIFY`      |
| `default `   | `self::MATCH_PARENT` |

:::
</dd>
</dl>
</div>

<div>

::: note Basic usage
```php
use Doubleedesign\Comet\Core\Alignment;
$result = Alignment::START;
$value = $result->value; // returns 'start'
```
:::
::: note Method usage

:::
</div>
</div>
<div class="data-type-doc">
<div>

## AspectRatio

Enum used to specify valid values for aspect ratio properties.

::: details Supported values

| Case                          | Value             |
|-------------------------------|-------------------|
| <code>STANDARD</code>         | <code>4:3</code>  |
| <code>PORTAIT</code>          | <code>3:4</code>  |
| <code>SQUARE</code>           | <code>1:1</code>  |
| <code>WIDE</code>             | <code>16:9</code> |
| <code>TALL</code>             | <code>9:16</code> |
| <code>CLASSIC</code>          | <code>3:2</code>  |
| <code>CLASSIC_PORTRAIT</code> | <code>2:3</code>  |

:::
### Methods
<dl>
<dt><code>tryFrom(string $value): ?self</code></dt>
<dd>Built-in PHP enum method that converts a string to the corresponding enum case, or returns null if the string is not a valid value.</dd>
</dl>
</div>

<div>

::: note Basic usage
```php
use Doubleedesign\Comet\Core\AspectRatio;
$result = AspectRatio::STANDARD;
$value = $result->value; // returns '4:3'
```
:::
::: note Method usage

```php
use Doubleedesign\Comet\Core\AspectRatio;
$result = AspectRatio::tryFrom('4:3');
```
:::
</div>
</div>
<div class="data-type-doc">
<div>

## ContainerSize

Enum used to specify valid values for container size properties.

::: details Supported values

| Case                   | Value                  |
|------------------------|------------------------|
| <code>WIDE</code>      | <code>wide</code>      |
| <code>FULLWIDTH</code> | <code>fullwidth</code> |
| <code>NARROW</code>    | <code>narrow</code>    |
| <code>NARROWER</code>  | <code>narrower</code>  |
| <code>SMALL</code>     | <code>small</code>     |
| <code>DEFAULT</code>   | <code>default</code>   |

:::
### Methods
<dl>
<dt><code>tryFrom(string $value): ?self</code></dt>
<dd>Built-in PHP enum method that converts a string to the corresponding enum case, or returns null if the string is not a valid value.</dd>
</dl>
</div>

<div>

::: note Basic usage
```php
use Doubleedesign\Comet\Core\ContainerSize;
$result = ContainerSize::WIDE;
$value = $result->value; // returns 'wide'
```
:::
::: note Method usage

```php
use Doubleedesign\Comet\Core\ContainerSize;
$result = ContainerSize::tryFrom('wide');
```
:::
</div>
</div>
<div class="data-type-doc">
<div>

## Orientation

Enum used to specify valid values for orientation properties.

::: details Supported values

| Case                    | Value                   |
|-------------------------|-------------------------|
| <code>HORIZONTAL</code> | <code>horizontal</code> |
| <code>VERTICAL</code>   | <code>vertical</code>   |

:::
### Methods
<dl>
<dt><code>tryFrom(string $value): ?self</code></dt>
<dd>Built-in PHP enum method that converts a string to the corresponding enum case, or returns null if the string is not a valid value.</dd>
</dl>
</div>

<div>

::: note Basic usage
```php
use Doubleedesign\Comet\Core\Orientation;
$result = Orientation::HORIZONTAL;
$value = $result->value; // returns 'horizontal'
```
:::
::: note Method usage

```php
use Doubleedesign\Comet\Core\Orientation;
$result = Orientation::tryFrom('horizontal');
```
:::
</div>
</div>
<div class="data-type-doc">
<div>

## Tag

Enum used to specify valid values for tag properties.

::: details Supported values

| Case       | Value      | Valid attributes                                                                                                                                                                                                                                                                                                                                                                                                          |
|------------|------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| A          | a          | `download`, `href`, `hreflang`, `ping`, `referrerpolicy`, `rel`, `target`, `type`, `aria-current`                                                                                                                                                                                                                                                                                                                         |
| ABBR       | abbr       | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| ABBR       | abbr       |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| ADDRESS    | address    | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| ADDRESS    | address    |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| ARTICLE    | article    | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| ARTICLE    | article    |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| ASIDE      | aside      | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| ASIDE      | aside      |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| AUDIO      | audio      | `autoplay`, `controls`, `controlslist`, `crossorigin`, `disableremoteplayback`, `loop`, `muted`, `preload`, `src`                                                                                                                                                                                                                                                                                                         |
| BDI        | bdi        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| BDI        | bdi        |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| BDO        | bdo        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| BDO        | bdo        |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| BLOCKQUOTE | blockquote | `cite`                                                                                                                                                                                                                                                                                                                                                                                                                    |
| BR         | br         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| BR         | br         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| BUTTON     | button     | `aria-expanded`, `aria-haspopup`, `aria-pressed`, `disabled`, `form`, `formaction`, `formenctype`, `formmethod`, `formnovalidate`, `formtarget`, `name`, `popovertarget`, `popovertargetaction`, `type`, `value`                                                                                                                                                                                                          |
| CANVAS     | canvas     | `height`, `width`                                                                                                                                                                                                                                                                                                                                                                                                         |
| CAPTION    | caption    | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| CAPTION    | caption    |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| CITE       | cite       | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| CITE       | cite       |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| CODE       | code       | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| CODE       | code       |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| COL        | col        | `span`                                                                                                                                                                                                                                                                                                                                                                                                                    |
| COLGROUP   | colgroup   | `span`                                                                                                                                                                                                                                                                                                                                                                                                                    |
| DATA       | data       | `value`                                                                                                                                                                                                                                                                                                                                                                                                                   |
| DATALIST   | datalist   | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| DATALIST   | datalist   |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| DD         | dd         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| DD         | dd         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| DEL        | del        | `cite`, `datetime`                                                                                                                                                                                                                                                                                                                                                                                                        |
| DETAILS    | details    | `open`, `name`                                                                                                                                                                                                                                                                                                                                                                                                            |
| DFN        | dfn        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| DFN        | dfn        |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| DIALOG     | dialog     | `open`, `aria-modal`                                                                                                                                                                                                                                                                                                                                                                                                      |
| DIV        | div        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| DIV        | div        |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| DL         | dl         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| DL         | dl         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| DT         | dt         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| DT         | dt         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| EM         | em         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| EM         | em         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| EMBED      | embed      | `height`, `src`, `type`, `width`                                                                                                                                                                                                                                                                                                                                                                                          |
| FIELDSET   | fieldset   | `disabled`, `form`, `name`                                                                                                                                                                                                                                                                                                                                                                                                |
| FIGCAPTION | figcaption | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| FIGCAPTION | figcaption |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| FIGURE     | figure     | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| FIGURE     | figure     |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| FOOTER     | footer     | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| FOOTER     | footer     |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| FORM       | form       | `accept-charset`, `autocomplete`, `name`, `rel`                                                                                                                                                                                                                                                                                                                                                                           |
| H1         | h1         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| H1         | h1         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| H2         | h2         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| H2         | h2         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| H3         | h3         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| H3         | h3         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| H4         | h4         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| H4         | h4         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| H5         | h5         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| H5         | h5         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| H6         | h6         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| H6         | h6         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| HEAD       | head       | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| HEAD       | head       |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| HEADER     | header     | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| HEADER     | header     |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| HGROUP     | hgroup     | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| HGROUP     | hgroup     |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| HR         | hr         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| HR         | hr         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| I          | i          | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| I          | i          |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| IFRAME     | iframe     | `allow`, `allowfullscreen`, `height`, `loading`, `name`, `referrerpolicy`, `sandbox`, `src`, `srcdoc`, `width`                                                                                                                                                                                                                                                                                                            |
| IMG        | img        | `alt`, `crossorigin`, `decoding`, `elementtiming`, `fetchpriority`, `height`, `ismap`, `loading`, `referrerpolicy`, `sizes`, `src`, `srcset`, `width`, `usemap`                                                                                                                                                                                                                                                           |
| INPUT      | input      | `accept`, `alt`, `aria-invalid`, `aria-required`, `autocomplete`, `capture`, `checked`, `dirname`, `disabled`, `form`, `formaction`, `formenctype`, `formmethod`, `formnovalidate`, `formtarget`, `height`, `list`, `max`, `maxlength`, `min`, `minlength`, `multiple`, `name`, `pattern`, `placeholder`, `popovertarget`, `popovertargetaction`, `readonly`, `required`, `size`, `src`, `step`, `type`, `value`, `width` |
| INS        | ins        | `cite`, `datetime`                                                                                                                                                                                                                                                                                                                                                                                                        |
| KBD        | kbd        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| KBD        | kbd        |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| LABEL      | label      | `for`                                                                                                                                                                                                                                                                                                                                                                                                                     |
| LEGEND     | legend     | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| LEGEND     | legend     |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| LI         | li         | `value`                                                                                                                                                                                                                                                                                                                                                                                                                   |
| MAIN       | main       | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| MAIN       | main       |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| MAP        | map        | `name`                                                                                                                                                                                                                                                                                                                                                                                                                    |
| MARK       | mark       | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| MARK       | mark       |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| META       | meta       | `charset`, `content`, `http-equiv`, `name`                                                                                                                                                                                                                                                                                                                                                                                |
| METER      | meter      | `aria-valuemin`, `aria-valuemax`, `aria-valuenow`, `value`, `min`, `max`, `low`, `high`, `optimum`, `form`                                                                                                                                                                                                                                                                                                                |
| NAV        | nav        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| NAV        | nav        |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| NOSCRIPT   | noscript   | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| NOSCRIPT   | noscript   |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| OBJECT     | object     | `data`, `form`, `height`, `name`, `type`, `width`                                                                                                                                                                                                                                                                                                                                                                         |
| OL         | ol         | `reversed`, `start`, `type`                                                                                                                                                                                                                                                                                                                                                                                               |
| OPTGROUP   | optgroup   | `disabled`, `label`                                                                                                                                                                                                                                                                                                                                                                                                       |
| OPTION     | option     | `disabled`, `label`, `selected`, `value`                                                                                                                                                                                                                                                                                                                                                                                  |
| OUTPUT     | output     | `for`, `form`, `name`                                                                                                                                                                                                                                                                                                                                                                                                     |
| P          | p          | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| P          | p          |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| PICTURE    | picture    | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| PICTURE    | picture    |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| PRE        | pre        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| PRE        | pre        |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| PROGRESS   | progress   | `aria-valuemin`, `aria-valuemax`, `aria-valuenow`, `max`, `value`                                                                                                                                                                                                                                                                                                                                                         |
| Q          | q          | `cite`                                                                                                                                                                                                                                                                                                                                                                                                                    |
| S          | s          | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| S          | s          |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| SAMP       | samp       | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| SAMP       | samp       |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| SEARCH     | search     | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| SEARCH     | search     |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| SECTION    | section    | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| SECTION    | section    |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| SELECT     | select     | `autocomplete`, `disabled`, `form`, `multiple`, `name`, `required`, `size`                                                                                                                                                                                                                                                                                                                                                |
| SMALL      | small      | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| SMALL      | small      |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| SOURCE     | source     | `type`, `src`, `srcset`, `sizes`, `media`, `height`, `width`                                                                                                                                                                                                                                                                                                                                                              |
| SPAN       | span       | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| SPAN       | span       |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| STRONG     | strong     | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| STRONG     | strong     |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| STYLE      | style      | `media`                                                                                                                                                                                                                                                                                                                                                                                                                   |
| SUB        | sub        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| SUB        | sub        |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| SUMMARY    | summary    | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| SUMMARY    | summary    |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| SUP        | sup        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| SUP        | sup        |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| TABLE      | table      | `aria-colcount`, `aria-rowcount`                                                                                                                                                                                                                                                                                                                                                                                          |
| TBODY      | tbody      | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| TBODY      | tbody      |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| TD         | td         | `colspan`, `headers`, `rowspan`                                                                                                                                                                                                                                                                                                                                                                                           |
| TEXTAREA   | textarea   | `aria-invalid`, `aria-required`, `autocomplete`, `autocorrect`, `cols`, `dirname`, `disabled`, `form`, `maxlength`, `minlength`, `name`, `placeholder`, `readonly`, `required`, `rows`, `wrap`                                                                                                                                                                                                                            |
| TFOOT      | tfoot      | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| TFOOT      | tfoot      |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| TH         | th         | `abbr`, `colspan`, `headers`, `rowspan`, `scope`                                                                                                                                                                                                                                                                                                                                                                          |
| THEAD      | thead      | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| THEAD      | thead      |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| TIME       | time       | `datetime`                                                                                                                                                                                                                                                                                                                                                                                                                |
| TITLE      | title      | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| TITLE      | title      |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| TR         | tr         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| TR         | tr         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| TRACK      | track      | `default`, `kind`, `label`, `src`, `srclang`                                                                                                                                                                                                                                                                                                                                                                              |
| U          | u          | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| U          | u          |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| UL         | ul         | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| UL         | ul         |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| VAR        | var        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| VAR        | var        |                                                                                                                                                                                                                                                                                                                                                                                                                           |
| VIDEO      | video      | `autoplay`, `controls`, `controlslist`, `crossorigin`, `disablepictureinpicture`, `disableremoteplayback`, `height`, `loop`, `muted`, `playsinline`, `poster`, `preload`, `src`, `width`                                                                                                                                                                                                                                  |
| WBR        | wbr        | Global attributes                                                                                                                                                                                                                                                                                                                                                                                                         |
| WBR        | wbr        |                                                                                                                                                                                                                                                                                                                                                                                                                           |

:::

::: details Global attributes
<ul>
<li><code>accesskey</code></li><li><code>aria-label</code></li><li><code>aria-labelledby</code></li><li><code>aria-describedby</code></li><li><code>aria-controls</code></li><li><code>autocapitalize</code></li><li><code>autofocus</code></li><li><code>class</code></li><li><code>contenteditable</code></li><li><code>data-*</code></li><li><code>dir</code></li><li><code>draggable</code></li><li><code>enterkeyhint</code></li><li><code>exportparts</code></li><li><code>hidden</code></li><li><code>id</code></li><li><code>inert</code></li><li><code>inputmode</code></li><li><code>is</code></li><li><code>itemid</code></li><li><code>itemprop</code></li><li><code>itemref</code></li><li><code>itemscope</code></li><li><code>itemtype</code></li><li><code>lang</code></li><li><code>nonce</code></li><li><code>part</code></li><li><code>popover</code></li><li><code>slot</code></li><li><code>spellcheck</code></li><li><code>style</code></li><li><code>tabindex</code></li><li><code>title</code></li><li><code>translate</code></li><li><code>writingsuggestions</code></li>
</ul>
:::
### Methods
<dl>
<dt><code>tryFrom(string $value): ?self</code></dt>
<dd>Built-in PHP enum method that converts a string to the corresponding enum case, or returns null if the string is not a valid value.</dd><dt><code>get_valid_attributes(): array</code></dt>
<dd>Returns an array of valid attributes</dd>
</dl>
</div>

<div>

::: note Basic usage
```php
use Doubleedesign\Comet\Core\Tag;
$result = Tag::A;
$value = $result->value; // returns 'a'
```
:::
::: note Method usage

```php
use Doubleedesign\Comet\Core\Tag;
$result = Tag::tryFrom('a');
```
```php
use Doubleedesign\Comet\Core\Tag; 
$someTag = Tag::A;
$result = $someTag->get_valid_attributes();
```
:::
</div>
</div>
<div class="data-type-doc">
<div>

## ThemeColor

Enum used to specify valid values for theme color properties.

::: details Supported values

| Case                   | Value                  |
|------------------------|------------------------|
| <code>PRIMARY</code>   | <code>primary</code>   |
| <code>SECONDARY</code> | <code>secondary</code> |
| <code>ACCENT</code>    | <code>accent</code>    |
| <code>ERROR</code>     | <code>error</code>     |
| <code>SUCCESS</code>   | <code>success</code>   |
| <code>WARNING</code>   | <code>warning</code>   |
| <code>INFO</code>      | <code>info</code>      |
| <code>LIGHT</code>     | <code>light</code>     |
| <code>DARK</code>      | <code>dark</code>      |
| <code>WHITE</code>     | <code>white</code>     |

:::
### Methods
<dl>
<dt><code>tryFrom(string $value): ?self</code></dt>
<dd>Built-in PHP enum method that converts a string to the corresponding enum case, or returns null if the string is not a valid value.</dd>
</dl>
</div>

<div>

::: note Basic usage
```php
use Doubleedesign\Comet\Core\ThemeColor;
$result = ThemeColor::PRIMARY;
$value = $result->value; // returns 'primary'
```
:::
::: note Method usage

```php
use Doubleedesign\Comet\Core\ThemeColor;
$result = ThemeColor::tryFrom('primary');
```
:::
</div>
</div>
