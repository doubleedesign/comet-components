<div @class($classes) @attributes($attributes)>
	@if ($children)
		@foreach ($children as $child)
			{{ $child->render() }}
		@endforeach
	@endif
</div>
