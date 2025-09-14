<a @class($classes) @attributes($attributes)>
	@if ($icon)
		<i class="{{ $iconPrefix }} {{ $icon }}"></i>
	@endif
	<span>{!! $content !!}</span>
</a>
