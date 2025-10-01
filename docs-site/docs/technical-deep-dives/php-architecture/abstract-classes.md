# Abstract Component Classes
Foundational PHP classes for defining common fields and methods for components.
<div class="abstract-class-doc" id="Renderable">
	
## Renderable

<table>
		<tr>
		<th scope="row" rowspan="2">Extended by</th>
		<td>
			<ul><li><code>DateComponent</code></li><li><code>ImageComponent</code></li><li><code>TextElement</code></li><li><code>UIComponent</code></li></ul>
		</td>
	</tr>
	<tr>
		<td>
			<ul><li><code>File</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row">Properties</th>
			<td><ul><li><code>classes</code></li><li><code>context</code></li><li><code>id</code></li><li><code>style</code></li><li><code>tagName</code></li><li><code>shortName</code></li><li><code>testId</code></li></ul></td>
	</tr>
</table>
</div><div class="abstract-class-doc" id="UIComponent">
	
## UIComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>Renderable</code></td></tr> 	<tr>
		<th scope="row">Extended by</th>
		<td>
			<ul><li><code>LayoutComponent</code></li><li><code>PanelComponent</code></li><li><code>PanelGroupComponent</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>classes</code></li><li><code>context</code></li><li><code>id</code></li><li><code>style</code></li><li><code>tagName</code></li><li><code>testId</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="LayoutComponent">
	
## LayoutComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>UIComponent</code></td></tr> 	<tr>
		<th scope="row" rowspan="2">Extended by</th>
		<td>
			<ul><li><code>WrappedLayoutComponent</code></li></ul>
		</td>
	</tr>
	<tr>
		<td>
			<ul><li><code>Column</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>UIComponent</code> <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>backgroundColor</code></li><li><code>hAlign</code></li><li><code>vAlign</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="WrappedLayoutComponent">
	
## WrappedLayoutComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>LayoutComponent</code></td></tr> 
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>LayoutComponent</code> <code>UIComponent</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>backgroundColor</code></li><li><code>classes</code></li><li><code>context</code></li><li><code>hAlign</code></li><li><code>id</code></li><li><code>isNested</code></li><li><code>style</code></li><li><code>tagName</code></li><li><code>testId</code></li><li><code>vAlign</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="ImageComponent">
	
## ImageComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>Renderable</code></td></tr> 	<tr>
		<th scope="row">Extended by</th>
		<td>
			<ul><li><code>ContentImageComponent</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>alt</code></li><li><code>classes</code></li><li><code>context</code></li><li><code>id</code></li><li><code>src</code></li><li><code>style</code></li><li><code>tagName</code></li><li><code>testId</code></li><li><code>title</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="ContentImageComponent">
	
## ContentImageComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>ImageComponent</code></td></tr> 
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>ImageComponent</code> <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>align</code></li><li><code>alt</code></li><li><code>aspectRatio</code></li><li><code>caption</code></li><li><code>classes</code></li><li><code>context</code></li><li><code>id</code></li><li><code>src</code></li><li><code>style</code></li><li><code>tagName</code></li><li><code>testId</code></li><li><code>title</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="TextElement">
	
## TextElement

<table>
	<tr><th scope='row'>Extends</th><td><code>Renderable</code></td></tr> 	<tr>
		<th scope="row">Extended by</th>
		<td>
			<ul><li><code>TextElementExtended</code></li></ul>
		</td>
	</tr>
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>classes</code></li><li><code>id</code></li><li><code>style</code></li><li><code>tagName</code></li><li><code>testId</code></li><li><code>textAlign</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="TextElementExtended">
	
## TextElementExtended

<table>
	<tr><th scope='row'>Extends</th><td><code>TextElement</code></td></tr> 
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
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>UIComponent</code> <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>backgroundColor</code></li><li><code>classes</code></li><li><code>colorTheme</code></li><li><code>context</code></li><li><code>id</code></li><li><code>isNested</code></li><li><code>orientation</code></li><li><code>size</code></li><li><code>style</code></li><li><code>tagName</code></li><li><code>testId</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="PanelComponent">
	
## PanelComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>UIComponent</code></td></tr> 
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>UIComponent</code> <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>backgroundColor</code></li><li><code>classes</code></li><li><code>context</code></li><li><code>id</code></li><li><code>style</code></li><li><code>subtitle</code></li><li><code>tagName</code></li><li><code>testId</code></li><li><code>title</code></li></ul></td></tr>
</table>	
</div><div class="abstract-class-doc" id="DateComponent">
	
## DateComponent

<table>
	<tr><th scope='row'>Extends</th><td><code>Renderable</code></td></tr> 
	<tr>
		<th scope="row" rowspan="2">Properties</th>
		<td><ul><li>All properties from <code>Renderable</code></li></ul></td>
	</tr>
	<tr>	<td><ul><li><code>colorTheme</code></li><li><code>locale</code></li><li><code>showDay</code></li><li><code>showYear</code></li></ul></td></tr>
</table>	
</div>
