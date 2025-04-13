# Abstract Component Classes
Foundational PHP classes for defining common fields and methods for components.
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
			<ul><li><code>Button</code></li><li><code>Embed</code></li><li><code>Image</code></li><li><code>Link</code></li><li><code>Separator</code></li><li><code>Table</code></li><li><code>File</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row">Properties</th>
			<td><ul><li><code>classes</code></li><li><code>context</code></li><li><code>id</code></li><li><code>style</code></li><li><code>tagName</code></li></ul></td>
	</tr>
</table>
</div><div class="abstract-class-doc" id="UIComponent">
	
## UIComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>Renderable</code></td></tr>
		<tr>
		<th scope="row" rowspan="2">Extended by</th>
		<td>
			<ul><li><code>LayoutComponent</code></li><li><code>PanelComponent</code></li><li><code>PanelGroupComponent</code></li></ul>
		</td>
	</tr>
	<tr>
		<td>
			<ul><li><code>Banner</code></li><li><code>Breadcrumbs</code></li><li><code>ButtonGroup</code></li><li><code>CallToAction</code></li><li><code>Callout</code></li><li><code>Container</code></li><li><code>Details</code></li><li><code>FileGroup</code></li><li><code>Gallery</code></li><li><code>Group</code></li><li><code>IconWithText</code></li><li><code>ImageAndText</code></li><li><code>LinkGroup</code></li><li><code>ListComponent</code></li><li><code>Menu</code></li><li><code>PageHeader</code></li><li><code>SiteFooter</code></li><li><code>MenuList</code></li><li><code>MenuListItem</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>Renderable</code></li></ul></td>
	</tr>
	<tr></tr>
</table>	
</div><div class="abstract-class-doc" id="LayoutComponent">
	
## LayoutComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>UIComponent</code></td></tr>
		<tr>
		<th scope="row">Extended by</th>
		<td>
			<ul><li><code>Columns</code></li><li><code>SiteHeader</code></li><li><code>Steps</code></li><li><code>Column</code></li><li><code>ContentWrapper</code></li><li><code>ImageWrapper</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>UIComponent</code> <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>backgroundColor</code></li><li><code>hAlign</code></li><li><code>vAlign</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="TextElement">
	
## TextElement

<table>
	<tr><th scope='row'>Extends</th><td><code>Renderable</code></td></tr>
		<tr>
		<th scope="row" rowspan="2">Extended by</th>
		<td>
			<ul><li><code>TextElementExtended</code></li></ul>
		</td>
	</tr>
	<tr>
		<td>
			<ul><li><code>TableCaption</code></li><li><code>TableCell</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>textAlign</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="TextElementExtended">
	
## TextElementExtended

<table>
	<tr><th scope='row'>Extends</th><td><code>TextElement</code></td></tr>
		<tr>
		<th scope="row">Extended by</th>
		<td>
			<ul><li><code>Heading</code></li><li><code>Paragraph</code></li><li><code>Pullquote</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>TextElement</code> <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>textColor</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="PanelGroupComponent">
	
## PanelGroupComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>UIComponent</code></td></tr>
		<tr>
		<th scope="row">Extended by</th>
		<td>
			<ul><li><code>Accordion</code></li><li><code>ResponsivePanels</code></li><li><code>Tabs</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>UIComponent</code> <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>colorTheme</code></li><li><code>orientation</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="PanelComponent">
	
## PanelComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>UIComponent</code></td></tr>
		<tr>
		<th scope="row">Extended by</th>
		<td>
			<ul><li><code>AccordionPanel</code></li><li><code>ResponsivePanel</code></li><li><code>TabPanel</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>UIComponent</code> <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>subtitle</code></li><li><code>title</code></li></ul></td></tr>
</table>	
</div>
