@opentag($tag) @class($outerClasses) @attributes($outerAttributes)>
	@if ($beforeComponents)
		@foreach ($beforeComponents as $child)
			@if (gettype($child) === 'object' && method_exists($child, 'render'))
				{{ $child->render() }}
			@endif
		@endforeach
	@endif
	<div data-vue-component="accordion">
		<accordion @class($classes) @attributes($attributes) icon="{{ $icon }}" :panels="@js($panels)">
		</accordion>
	</div>
@closetag($tag)
