<a @class($classes) @attributes($attributes)>
	@if ($icon)
		<span class="{{ $bemPrefix }}__icon">
			<i class="{{ $iconPrefix }} {{ $icon }}"></i>
		</span>
	@endif
	@include('components.Link.partials.link-text')
</a>
