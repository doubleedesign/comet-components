<div @if ($classes) @class($classes) @endif @attributes($attributes) role="group">
    @foreach ($children as $child)
        @if (method_exists($child, 'render'))
            {{ $child->render() }}
        @endif
    @endforeach
</div>
