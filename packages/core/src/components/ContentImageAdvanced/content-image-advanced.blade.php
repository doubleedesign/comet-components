<figure @class($classes) @attributes($outerAttrs)>
	<div class="{{ $bemPrefix }}__image" data-aspect-ratio="{{ $aspectRatio }}">
		<div class="{{ $bemPrefix }}__image__inner">
			<img src="{{ $src }}" @attributes($attributes)>
		</div>
	</div>
	@if ($caption)
		<figcaption class="${{ $bemPrefix }}__caption">{{ $caption }}</figcaption>
	@endif
</figure>
