{{-- @var string $tag --}}
{{-- @var array<string,string> $attributes --}}
{{-- @var string $content --}}
<{{ $tag }} @attributes($attributes)>
{!! $content !!}
</{{ $tag }}>
