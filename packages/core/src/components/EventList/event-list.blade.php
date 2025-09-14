<div class="events">
	@if ($heading)
		@if (method_exists($heading, 'render'))
			{{ $heading->render() }}
		@endif
	@endif
	@opentag($tag) @if ($classes)
		@class($classes)
	@endif @attributes($attributes)>
	<blade-fragment>
		@include('components._blade-partials.children')
	</blade-fragment>
	@closetag($tag)
</div>
