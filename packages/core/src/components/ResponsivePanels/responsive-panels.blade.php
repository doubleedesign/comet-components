<div data-vue-component="responsive-panels" @if ($classes) @class($classes) @endif>
	<responsive-panels @attributes($attributes) breakpoint="{{ $breakpoint }}" icon="{{ $icon }}" :panels="{{ $panels }}">
	</responsive-panels>
</div>
