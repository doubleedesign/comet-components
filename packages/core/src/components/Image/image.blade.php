<x-media class="image">
	@if($caption)
		<figure>
			@if($link)
				<a href="{{ $link }}">
					<img src="{{ $src }}" @class($classes) @attributes($attributes)>
				</a>
			@else
				<img src="{{ $src }}" @class($classes) @attributes($attributes)>
			@endif
			<figcaption>{{ $caption }}</figcaption>
		</figure>
	@else
		@if($link)
			<a href="{{ $link }}">
				<img src="{{ $src }}" @class($classes) @attributes($attributes)>
			</a>
		@else
			<img src="{{ $src }}" @class($classes) @attributes($attributes)>
		@endif
	@endif
</x-media>
