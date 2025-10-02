@opentag($tag) @class($classes) @attributes($outerAttrs)>
<div class="{{ $bemPrefix }}__image" data-aspect-ratio="{{ $aspectRatio }}">
	<div class="{{ $bemPrefix }}__image__inner">
		<img @attributes($attributes)>
	</div>
</div>
@if ($caption)
	@if ($tag === 'figure')
		<figcaption class="${{ $bemPrefix }}__caption">{{ $caption }}</figcaption>
	@else
		<div class="{{ $bemPrefix }}__caption">{{ $caption }}</div>
	@endif
@endif
@closetag($tag)
