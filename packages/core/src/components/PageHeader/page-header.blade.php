<header @if ($outerClasses) @class($outerClasses) @endif @attributes($attributes)>
	<div @if ($classes) @class($classes) @endif @attributes($innerAttributes)>
		@foreach ($children as $child)
			@if (gettype($child) === 'object' && method_exists($child, 'render'))
				{{ $child->render() }}
			@endif
		@endforeach
	</div>
</header>
