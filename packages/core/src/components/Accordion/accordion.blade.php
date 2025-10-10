@if ($intro)
	@if (gettype($intro) === 'object' && method_exists($intro, 'render'))
		{{ $intro->render() }}
	@endif
@endif

<div data-vue-component="accordion">
	<accordion @class($classes) @attributes($attributes) icon="{{ $icon }}" :panels="@js($panels)">
	</accordion>
</div>
