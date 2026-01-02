<a @class($classes) @attributes($attributes)>
	@if ($icon)
		<i class="{{ $iconPrefix }} {{ $icon }}"></i>
	@endif
	@include('components.Link.partials.link-text')
</a>
