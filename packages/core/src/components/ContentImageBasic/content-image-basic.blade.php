@if ($caption)
	<figure @class($wrapperClasses) @attributes($outerAttrs)>
		@if ($href)
			<a @attributes($innerAttrs) href="{{ $href }}" @if ($caption) data-caption="{{ $caption }}" @endif>
				<img src="{{ $src }}" @attributes($attributes)>
			</a>
		@else
			<div @class($classes) @attributes($innerAttrs)>
				<img src="{{ $src }}" @attributes($attributes)>
			</div>
		@endif
		<figcaption @class($captionClasses)>{{ $caption }}</figcaption>
	</figure>
@else
	@if ($href)
		<a href="{{ $href }}" @class($classes) @attributes([...$outerAttrs, ...$innerAttrs]) @if ($caption) data-caption="{{ $caption }}" @endif>
			<img src="{{ $src }}" @attributes($attributes)>
		</a>
	@else
		<div @class($classes) @attributes([...$outerAttrs, ...$innerAttrs])>
			<img src="{{ $src }}" @attributes($attributes)>
		</div>
	@endif
@endif
