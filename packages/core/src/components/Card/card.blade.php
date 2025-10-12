@opentag($tag) @class($classes) @attributes($attributes)>
<blade-fragment>
	@if($image)
		@include('components.Card.partials.card-image')
	@endif
	@include('components.Card.partials.card-body')
</blade-fragment>
@closetag($tag)
