@opentag($tag) @class($classes) @attributes($attributes)>
<blade-fragment>
	<span>
		{!! $content !!}
	</span>
	@if ($tooltip)
		<span data-tippy-content="{{ $tooltip }}" tabindex="0">
			<i class="{{ $iconPrefix }} {{ $icon }}"></i>
		</span>
	@endif
</blade-fragment>
@closetag($tag)
