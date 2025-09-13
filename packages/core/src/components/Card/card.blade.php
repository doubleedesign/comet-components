<{{ $tag }} class="card-wrapper">
	<div class="{{ $bemName }}" @class($classes) @attributes($attributes)>
		<div class="{{ $bemName }}__image" @attributes($imageWrapperAttrs)>
			<img @attributes($image) />
		</div>
		<div class="{{ $bemName }}__content">
			@foreach ($children as $child)
				@if (method_exists($child, 'render'))
					{{ $child->render() }}
				@endif
			@endforeach
		</div>
	</div>
	</{{ $tag }}>
