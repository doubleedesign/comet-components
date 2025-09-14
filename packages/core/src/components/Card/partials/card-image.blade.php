@if ($image)
	<div class="{{ $shortName }}__image {{ $bemName }}__image" @attributes($imageWrapperAttrs)>
		<img @attributes($image) />
	</div>
@endif
