@opentag($tag) @class($classes) @attributes($attributes)>
<div class="{{ $bemPrefix }}__image">
	<div class="{{ $bemPrefix }}__image__frame" @attributes($imageWrapperAttrs)>
		<div class="{{ $bemPrefix }}__image__frame__inner">
			<img @attributes($imageAttrs) />
		</div>
	</div>
</div>
@include('components._blade-partials.children')
@closetag($tag)
