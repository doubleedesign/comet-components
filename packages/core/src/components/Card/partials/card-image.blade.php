@if ($image)
	<div class="{{ $bemName }}__image" @attributes($imageWrapperAttrs)>
		<img @attributes($image) />
	</div>
@endif
