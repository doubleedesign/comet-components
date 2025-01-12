{{-- @var string $tag --}}
{{-- @var string $classes --}}
{{-- @var array<string,string> $attributes --}}
{{-- @var string $content --}}
<{{ $tag }} @class($classes) @attributes($attributes)>
	{!! $content !!}
</{{ $tag }}>
