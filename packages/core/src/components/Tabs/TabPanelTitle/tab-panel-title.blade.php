<li role="presentation" @if ($classes) @class($classes) @endif @attributes($attributes)>
    <a role="tab" href="#{{ $anchor }}" aria-controls="{{ $anchor }}">
        {!! $content !!}
    </a>
</li>
