<div @class($classes) @attributes($attributes)>
	@if ($icon)
		<i class="{{ $iconPrefix }} {{ $icon }}"></i>
	@endif
	<div class="callout__content">
		@include('components._blade-partials.children')
	</div>
</div>
