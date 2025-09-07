@if (isset($beforeComponents) && is_array($beforeComponents))
	<div @attributes($introAttributes)>
		@foreach ($beforeComponents as $item)
			@if (gettype($item) === 'object' && method_exists($item, 'render'))
				{{ $item->render() }}
			@endif
		@endforeach
	</div>
@endif
<div data-vue-component="accordion">
	<accordion @class($classes) @attributes($attributes) icon="{{ $icon }}" :panels="@js($panels)">
	</accordion>
</div>
