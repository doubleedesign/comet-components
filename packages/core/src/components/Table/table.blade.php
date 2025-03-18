<table @if ($classes) @class($classes) @endif @attributes($attributes)>
    @if (isset($caption) && $caption && method_exists($caption, 'render'))
        {{ $caption->render() }}
    @endif
    @if (!empty($thead))
        <thead class="table__header">
            {{-- $thead is an indexed array of rows, which each contain an indexed array of cells --}}
            {{-- Read this as for each $row in $thead --}}
            @foreach ($thead as $row)
                <tr class="table__header__row">
                    {{-- Read this as for each $cell in $row --}}
                    @foreach ($row as $cell)
                        @if (method_exists($cell, 'render'))
                            {{ $cell->render() }}
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </thead>
    @endif
    @if (!empty($tbody))
        <tbody class="table__body">
            {{-- $tbody is an indexed array of rows, which each contain an indexed array of cells --}}
            {{-- Read this as for each $row in $tbody --}}
            @foreach ($tbody as $row)
                <tr class="table__body__row">
                    {{-- Read this as for each $cell in $row --}}
                    @foreach ($row as $cell)
                        @if (method_exists($cell, 'render'))
                            {{ $cell->render() }}
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    @endif
    @if (!empty($tfoot))
        <tfoot class="table__footer">
            {{-- $tfoot is an indexed array of rows, which each contain an indexed array of cells --}}
            {{-- Read this as for each $row in $tfoot --}}
            @foreach ($tfoot as $row)
                <tr class="table__footer__row">
                    {{-- Read this as for each $cell in $row --}}
                    @foreach ($row as $cell)
                        @if (method_exists($cell, 'render'))
                            {{ $cell->render() }}
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tfoot>
    @endif
</table>
