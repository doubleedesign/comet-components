@if ($caption)
    <figure @if ($classes) @class($classes) @endif>
        @if ($href)
            <a href="{{ $href }}">
                <img src="{{ $src }}" @attributes($attributes)>
            </a>
        @else
            <img src="{{ $src }}" @class($classes) @attributes($attributes)>
        @endif
        <figcaption>{{ $caption }}</figcaption>
    </figure>
@else
    @if ($href)
        <a href="{{ $href }}" @if ($classes) @class($classes) @endif>
            <img src="{{ $src }}" @attributes($attributes)>
        </a>
    @else
        <img src="{{ $src }}" @if ($classes) @class($classes) @endif
            @attributes($attributes)>
    @endif
@endif
<!-- TODO: Handle caption AND link present at the same time -->
