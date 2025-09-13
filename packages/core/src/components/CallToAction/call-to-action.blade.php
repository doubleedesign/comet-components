<{{ $tag }} @if ($outerClasses) @class($outerClasses) @endif @attributes($attributes)>
	@foreach ($children as $child)
		@if (gettype($child) === 'object' && method_exists($child, 'render'))
			{{ $child->render() }}
		@endif
	@endforeach
	</{{ $tag }}>
