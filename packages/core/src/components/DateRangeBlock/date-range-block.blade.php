<time @if ($classes) @class($classes) @endif @attributes($attributes)>
	@if ($days !== null)
		<span class="date-range-block__days">
			{{ $days }}
		</span>
	@endif
	<span class="date-range-block__dates">
		{{ $dates }}
	</span>
	<span class="date-range-block__month">
		{{ $month }}
	</span>
	@if ($year !== null)
		<span class="date-range-block__year">
			{{ $year }}
		</span>
	@endif
</time>
