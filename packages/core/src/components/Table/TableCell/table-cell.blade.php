{{-- blade-formatter-disable --}}
@opentag($tag) @if ($classes) @class($classes) @endif @attributes($attributes)>
{{-- blade-formatter-enable --}}
@if ($innerComponents)
	@include('components._blade-partials.children', ['children' => $innerComponents])
@else
	{!! $content !!}
@endif
@closetag($tag)






