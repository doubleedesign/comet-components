<table @class($classes) @attributes($attributes)>
	@if (isset($caption) && $caption && method_exists($caption, 'render'))
		{{ $caption->render() }}
	@endif
	@if (!empty($thead))
		<thead class="{{ $bemPrefix }}__header">
			{{-- $thead is an indexed array of rows, which each contain an indexed array of cells --}}
			{{-- Read this as for each $row in $thead --}}
			@foreach ($thead as $row)
				<tr @attributes($row['attributes'] ?? [])>
					@foreach ($row['cells'] as $cell)
						@if (method_exists($cell, 'render'))
							{{ $cell->render() }}
						@endif
					@endforeach
				</tr>
			@endforeach
		</thead>
	@endif
	@if (!empty($tbody))
		@if (array_is_list($tbody))
			{{-- $tbody is an indexed array of multiple tbody elements each containing an indexed array of cells --}}
			@foreach ($tbody as $thisTbody)
				{{-- The tbody itself has custom attributes --}}
				@if ($thisTbody['attributes'])
					@include('components._blade-partials.tbody', [
						// Merge in any classes passed in as tbody attributes
						'classes' => isset($thisTbody['attributes']['classes'])
							? join(' ', ["{$bemPrefix}__body", ...$thisTbody['attributes']['classes']])
							: "{$bemPrefix}__body",
						// Pass attributes without classes (if present) to the partial
						'attributes' => array_diff_key($thisTbody['attributes'], ['classes' => '']),
						'rows' => $thisTbody['rows'],
					])
				@else
					@include('components._blade-partials.tbody', [
						'classes' => ["{$bemPrefix}__body"],
						'attributes' => [],
						'rows' => $thisTbody['rows'],
					])
				@endif
			@endforeach
		@else
			{{-- $tbody is a single row, which contains an indexed array of cells. --}}
			@include('components._blade-partials.tbody', [
				'classes' => ["{$bemPrefix}__body"],
				'attributes' => [],
				'rows' => $tbody,
			])
		@endif
		@if (!empty($tfoot))
			<tfoot class="{{ $bemPrefix }}__footer">
				{{-- $tfoot is an indexed array of rows, which each contain an indexed array of cells --}}
				{{-- Read this as for each $row in $tfoot --}}
				@foreach ($tfoot as $row)
					<tr @attributes($row['attributes'] ?? [])>
						@foreach ($row['cells'] as $cell)
							@if (method_exists($cell, 'render'))
								{{ $cell->render() }}
							@endif
						@endforeach
					</tr>
				@endforeach
			</tfoot>
		@endif
	@endif
</table>
