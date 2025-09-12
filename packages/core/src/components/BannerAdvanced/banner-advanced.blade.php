<{{ $tag }} @if ($classes) @class($classes) @endif @attributes($attributes)>
	{!! $content !!}
	@foreach ($children as $child)
		@if (method_exists($child, 'render'))
			{{ $child->render() }}
		@endif
	@endforeach
	</{{ $tag }}>
