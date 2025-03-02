<div @if ($classes) @class($classes) @endif @attributes($attributes)>
    @if ($icon)
        <div class="icon-with-text__icon">
            <i class="{{ $iconPrefix }} {{ $icon }}"></i>
        </div>
    @endif
    <div class="icon-with-text__content">
         @foreach ($children as $child)
            @if (method_exists($child, 'render'))
                {{ $child->render() }}
            @endif
        @endforeach
</div>
</div>
