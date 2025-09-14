<figure @class($classes) @attributes($attributes)>
	<blade-fragment>
		@include('components._blade-partials.children')
	</blade-fragment>
	@if ($caption)
		<figcaption class="gallery__caption">{!! $caption !!}</figcaption>
	@endif
</figure>
