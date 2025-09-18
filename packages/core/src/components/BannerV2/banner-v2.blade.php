@opentag($tag) @class($classes) @attributes($attributes)>
<div class="{{ $bemPrefix }}__image">
	<div class="{{ $bemPrefix }}__image__frame" @attributes($imageWrapperAttrs)>
		<div class="{{ $bemPrefix }}__image__frame__inner">
			<img @attributes($imageAttrs) />
		</div>
	</div>
</div>
<div class="{{ $bemPrefix }}__content">
	@include('components._blade-partials.children')
</div>
@closetag($tag)
