<div @class($classes) @attributes($attributes) role="group">
	@foreach ($items as $item)
		<a class="{{ $itemClass }}" href="{{ $item['url'] }}" title="{{ $item['label'] }}" data-tippy-content="{{ $item['label'] }}" target="_blank">
			<i class="{{ $iconPrefix }} {{ $item['icon'] }}"></i>
		</a>
	@endforeach
</div>
