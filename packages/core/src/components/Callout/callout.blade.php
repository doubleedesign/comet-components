<div @if ($classes) @class($classes) @endif @attributes($attributes)>
    @if ($icon)
        <i class="{{ $iconPrefix }} {{ $icon }}"></i>
    @endif
    <div class="callout__content">
        @foreach ($children as $child)
            @if (method_exists($child, 'render'))
                {{ $child->render() }}
            @endif
        @endforeach
    </div>
</div>
