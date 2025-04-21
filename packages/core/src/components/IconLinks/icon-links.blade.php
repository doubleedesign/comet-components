<div @if ($classes) @class($classes) @endif @attributes($attributes) role="group">
	@foreach ($items as $item)
		<a class="icon-links__item" href="{{ $item['url'] }}" title="{{ $item['label'] }}" data-tippy-content="{{ $item['label'] }}" target="_blank">
			<i class="{{ $iconPrefix }} {{ $item['icon'] }}"></i>
		</a>
	@endforeach
</div>
