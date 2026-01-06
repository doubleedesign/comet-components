@if ($isWrapped)
	<blade-fragment>
		@include('components._blade-partials.children')
	</blade-fragment>
@else
	@opentag($tag) @class($classes) @attributes($attributes)>
	<blade-fragment>
		@include('components._blade-partials.children')
	</blade-fragment>
	@closetag($tag)
@endif
