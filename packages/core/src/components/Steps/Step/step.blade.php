<li @if($classes)@class($classes)@endif @attributes($attributes)>
	<div @if($innerClasses)@class($innerClasses)@endif>
		@foreach($children as $child)
			@if(method_exists($child, 'render'))
				{{ $child->render() }}
			@endif
		@endforeach
	</div>
</li>
