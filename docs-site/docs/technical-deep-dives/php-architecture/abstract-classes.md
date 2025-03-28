---
title: Abstract classes
position: 1
---

# Abstract Component Classes
Foundational PHP classes for defining common fields and methods for components.

<div id="abstract-classes">
<div class="abstract-class-doc" id="Renderable">

## Renderable

<table>
<tr>
<th scope="row" rowspan="2">Extended by</th>
<td>
<ul><li><code>TextElement</code></li><li><code>UIComponent</code></li></ul>
</td>
</tr>
<tr>
<td>
<ul><li><code>Button</code></li><li><code>Embed</code></li><li><code>Image</code></li><li><code>Link</code></li><li><code>Separator</code></li><li><code>Table</code></li></ul>
</td>
</tr>
<tr>
<th scope="row" rowspan="2">Properties</th>
<td></td>
</tr>
<tr>
<td>	<ul><li><code>classes</code></li><li><code>context</code></li><li><code>id</code></li><li><code>style</code></li><li><code>tagName</code></li></ul></td>
</tr>
</table>

</div>
<div class="abstract-class-doc" id="UIComponent">

## UIComponent

<table>
<tr><th scope='row'>Extends</th><td><code>Renderable</code></td></tr>
<tr>
<th scope="row" rowspan="2">Extended by</th>
<td>
<ul><li><code>LayoutComponent</code></li></ul>
</td>
</tr>
<tr>
<td>
<ul><li><code>Accordion</code></li><li><code>Banner</code></li><li><code>Breadcrumbs</code></li><li><code>ButtonGroup</code></li><li><code>CallToAction</code></li><li><code>Callout</code></li><li><code>Container</code></li><li><code>Details</code></li><li><code>FileGroup</code></li><li><code>Gallery</code></li><li><code>IconWithText</code></li><li><code>ImageAndText</code></li><li><code>LinkGroup</code></li><li><code>ListComponent</code></li><li><code>Menu</code></li><li><code>PageHeader</code></li><li><code>SiteFooter</code></li><li><code>Tabs</code></li></ul>
</td>
</tr>
<tr>
<th scope="row" rowspan="2">Properties</th>
<td><ul><li>All properties from <code>Renderable</code></li></ul></td>
</tr>
<tr>
<td></td>
</tr>
</table>

</div>
<div class="abstract-class-doc" id="TextElement">

## TextElement

<table>
<tr><th scope='row'>Extends</th><td><code>Renderable</code></td></tr>
<tr>
<th scope="row">Extended by</th>
<td>
<ul><li><code>TextElementExtended</code></li></ul>
</td>
</tr>
<tr>
<th scope="row" rowspan="2">Properties</th>
<td><ul><li>All properties from <code>Renderable</code></li></ul></td>
</tr>
<tr>
<td>	<ul><li><code>textAlign</code></li></ul></td>
</tr>
</table>

</div>
<div class="abstract-class-doc" id="LayoutComponent">

## LayoutComponent

<table>
<tr><th scope='row'>Extends</th><td><code>UIComponent</code></td></tr>
<tr>
<th scope="row" rowspan="2">Extended by</th>
<td>
<ul></ul>
</td>
</tr>
<tr>
<td>
<ul><li><code>Columns</code></li><li><code>Group</code></li><li><code>SiteHeader</code></li><li><code>Steps</code></li></ul>
</td>
</tr>
<tr>
<th scope="row" rowspan="2">Properties</th>
<td><ul><li>All properties from <code>UIComponent</code></li></ul></td>
</tr>
<tr>
<td>	<ul><li><code>backgroundColor</code></li><li><code>hAlign</code></li><li><code>vAlign</code></li></ul></td>
</tr>
</table>

</div>
<div class="abstract-class-doc" id="TextElementExtended">

## TextElementExtended

<table>
<tr><th scope='row'>Extends</th><td><code>TextElement</code></td></tr>
<tr>
<th scope="row" rowspan="2">Extended by</th>
<td>
<ul></ul>
</td>
</tr>
<tr>
<td>
<ul><li><code>Heading</code></li><li><code>Paragraph</code></li><li><code>Pullquote</code></li></ul>
</td>
</tr>
<tr>
<th scope="row" rowspan="2">Properties</th>
<td><ul><li>All properties from <code>TextElement</code></li></ul></td>
</tr>
<tr>
<td>	<ul><li><code>textColor</code></li></ul></td>
</tr>
</table>

</div></div>
