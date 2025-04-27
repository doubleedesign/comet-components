<div class="events">
	@if ($heading)
		@if (method_exists($heading, 'render'))
			{{ $heading->render() }}
		@endif
	@endif
	<{{ $tag }} @if ($classes) @class($classes) @endif @attributes($attributes)>
		@foreach ($children as $child)
			@if (method_exists($child, 'render'))
				{{ $child->render() }}
			@endif
		@endforeach
		</{{ $tag }}>
</div>
