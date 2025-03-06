<{{$tag}} @if ($classes) @class($classes) @endif @attributes($attributes)>
	<div class="column__inner">
	    @foreach ($children as $child)
	        @if (method_exists($child, 'render'))
	            {{ $child->render() }}
	        @endif
	    @endforeach
	</div>
</{{$tag}}>
