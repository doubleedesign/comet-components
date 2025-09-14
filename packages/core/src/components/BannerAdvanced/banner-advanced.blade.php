@opentag($tag) @if ($classes)
	@class($classes)
@endif @attributes($attributes)>
<blade-fragment>
	@include('components._blade-partials.children')
</blade-fragment>
@closetag($tag)
