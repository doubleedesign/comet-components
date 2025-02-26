@if($withWrapper)
	<{{ $tag }} @if($outerClasses)@class($outerClasses)@endif @attributes($attributes)>
		<div @if($classes)@class($classes)@endif>
			@foreach($children as $child)
				@if(method_exists($child, 'render'))
					{{ $child->render() }}
				@endif
			@endforeach
		</div>
	</{{ $tag }}>
@else
	<{{ $tag }} @if($classes)@class($classes)@endif @attributes($attributes)>
		@foreach($children as $child)
			@if(method_exists($child, 'render'))
				{{ $child->render() }}
			@endif
		@endforeach
	</{{ $tag }}>
@endif
