@if ($caption)
	<figure @if ($classes) @class($classes) @endif @attributes($outerAttrs)>
		@if ($href)
			<a @attributes($innerAttrs) href="{{ $href }}" @if ($caption) data-caption="{{ $caption }}" @endif>
				<img src="{{ $src }}" @attributes($attributes)>
			</a>
		@else
			<div @attributes($innerAttrs)>
				<img src="{{ $src }}" @attributes($attributes)>
			</div>
		@endif
		<figcaption @class($captionClasses)>{{ $caption }}</figcaption>
	</figure>
@else
	@if ($href)
		<a href="{{ $href }}" @if ($classes) @class($classes) @endif @attributes([...$outerAttrs, ...$innerAttrs])
					@if ($caption) data-caption="{{ $caption }}" @endif>
			<img src="{{ $src }}" @attributes($attributes)>
		</a>
	@else
		<div @if ($classes) @class($classes) @endif @attributes([...$outerAttrs, ...$innerAttrs])>
			<img src="{{ $src }}" @attributes($attributes)>
		</div>
	@endif
@endif
