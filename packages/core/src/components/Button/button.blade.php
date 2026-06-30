@php
$isPagination = array_find($classes, fn($class) => str_contains($class, 'pagination')) !== null;
$iconPrefix = Doubleedesign\Comet\Core\Config::getInstance()->get_icon_prefix();
@endphp

<{{ $tag }} @class($classes) @attributes($attributes)>
	@if(isset($iconBefore))
		<i class="{{ $iconPrefix }} {{ $iconBefore }}"></i>
	@endif
	<span class="button__label">{!! $content !!}</span>
	@if(isset($iconAfter))
		<i class="{{ $iconPrefix }} {{ $iconAfter }}"></i>
	@endif
</{{ $tag }}>
