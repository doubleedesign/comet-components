@opentag($tag) @class($classes) @attributes($attributes)>
<blade-fragment>
	@foreach ($children as $child)
		@if (method_exists($child, 'render'))
			{{ $child->render() }}
		@endif
	@endforeach
</blade-fragment>
@closetag($tag)
