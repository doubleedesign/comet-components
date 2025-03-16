<table @if ($classes) @class($classes) @endif @attributes($attributes)>
    @if (isset($caption) && method_exists($caption, 'render'))
        {{ $caption->render() }}
    @endif
    @if (!empty($thead))
        <thead class="table__header">
            @foreach ($thead as $row)
                <tr class="table__header__row">
                    @if (method_exists($cell, 'render'))
                        {{ $cell->render() }}
                    @endif
                </tr>
            @endforeach
        </thead>
    @endif
    @if (!empty($tbody))
        <tbody class="table__body">
            @foreach ($tbody as $row)
                <tr class="table__body__row">
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
            @foreach ($tfoot as $row)
                <tr class="table__footer__row">
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
