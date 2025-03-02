<li @if ($classes) @class($classes) @endif @attributes($attributes)>
    @if (!empty($content))
        {!! $content !!}
    @endif
    @if (!empty($children))
        @foreach ($children as $child)
            @if (method_exists($child, 'render'))
                {{ $child->render() }}
            @endif
        @endforeach
    @endif
</li>
