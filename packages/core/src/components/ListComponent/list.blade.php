@if($ordered)
	<ol @if($classes)@class($classes)@endif @attributes($attributes)>
		@foreach($children as $child)
			@if(method_exists($child, 'render'))
				{{ $child->render() }}
			@endif
		@endforeach
	</ol>
@else
	<ul @if($classes)@class($classes)@endif @attributes($attributes)>
		@foreach($children as $child)
			@if(method_exists($child, 'render'))
				{{ $child->render() }}
			@endif
		@endforeach
	</ul>
@endif
