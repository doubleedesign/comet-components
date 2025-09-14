@if ($withWrapper)
	@opentag($tag) @class($outerClasses) @attributes($attributes)>
	<div @if ($classes) @class($classes) @endif @attributes($innerAttributes)>
		@include('components._blade-partials.children')
	</div>
	@closetag($tag)
@else
	@opentag($tag) @class($classes) @attributes([...$attributes, ...$innerAttributes])>
	<blade-fragment>
		@include('components._blade-partials.children')
	</blade-fragment>
	@closetag($tag)
@endif
