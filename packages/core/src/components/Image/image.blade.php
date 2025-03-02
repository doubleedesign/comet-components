@if ($caption)
    <figure @if($classes) @class($classes) @endif>
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
        <div @if ($classes) @class($classes) @endif>
			<img src="{{ $src }}" @attributes($attributes)>
        </div>
    @endif
@endif
