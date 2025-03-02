<nav @if ($classes) @class($classes) @endif @attributes($attributes)>
    @foreach ($children as $child)
        @if (method_exists($child, 'render'))
            {{ $child->render() }}
        @endif
    @endforeach
</nav>
