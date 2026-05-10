@opentag($tag) @class($outerClasses) @attributes($outerAttributes)>
	<div data-vue-component="tabs">
		<tabs @class($classes) @attributes($attributes) :panels="@js($panels)">
		</tabs>
	</div>
@closetag($tag)
