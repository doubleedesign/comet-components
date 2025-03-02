<div role="tabpanel" @class($classes) @attributes($attributes)>
    @foreach ($children as $child)
        @if (method_exists($child, 'render'))
            {{ $child->render() }}
        @endif
    @endforeach
</div>
