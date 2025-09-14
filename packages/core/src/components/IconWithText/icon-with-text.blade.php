<div @class($classes) @attributes($attributes)>
	@if ($icon)
		<div class="icon-with-text__icon">
			<i class="{{ $iconPrefix }} {{ $icon }}"></i>
		</div>
	@endif
	<div class="icon-with-text__content">
		<blade-fragment>
			@include('components._blade-partials.children')
		</blade-fragment>
	</div>
</div>
