<tbody @class($classes) @attributes($attributes)>
	@foreach ($rows as $row)
		<tr @attributes($row['attributes'] ?? [])>
			@foreach ($row['cells'] as $cell)
				@if (method_exists($cell, 'render'))
					{{ $cell->render() }}
				@endif
			@endforeach
		</tr>
	@endforeach
</tbody>
