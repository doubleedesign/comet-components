<a @if ($classes) @class($classes) @endif @attributes($attributes) target="_blank">
	@if ($icon)
		<span class="{{ $bem_prefix }}__icon" aria-label="File type: {{ $mimeType }}">
			<i class="{{ $iconPrefix }} {{ $icon }}"></i>
		</span>
	@endif
	<span class="{{ $bem_prefix }}__content">
		<span class="{{ $bem_prefix }}__content__title">{{ $title }}</span>
		@if ($description)
			<span class="{{ $bem_prefix }}__content__description">{{ $description }}</span>
		@endif
		<span class="{{ $bem_prefix }}__meta">
			@if ($size)
				<span class="{{ $bem_prefix }}__content__meta__size">{{ $size }}</span>
			@endif
			@if ($uploadDate)
				<span class="{{ $bem_prefix }}__content__meta__date">{{ $uploadDate }}</span>
			@endif
		</span>
	</span>
</a>
