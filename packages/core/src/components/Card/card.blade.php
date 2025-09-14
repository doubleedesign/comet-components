<{{ $tag }} class="card-wrapper">
	<div @class($classes) @attributes($attributes)>
		@if ($image)
			<div class="{{ $shortName }}__image {{ $bemName }}__image" @attributes($imageWrapperAttrs)>
				<img @attributes($image) />
			</div>
		@endif
		<div class="{{ $shortName }}__content {{ $bemName }}__content">
			@foreach ($children as $child)
				@if (method_exists($child, 'render'))
					{{ $child->render() }}
				@endif
			@endforeach
		</div>
	</div>
	</{{ $tag }}>
