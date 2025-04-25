<time @if ($classes) @class($classes) @endif @attributes($attributes)>
	@if ($showDay)
		<span class="date-block__day">
			{{ $date->format('D') }}
		</span>
	@endif
	<span class="date-block__date">
		{{ $date->format('d') }}
	</span>
	<span class="date-block__month">
		{{ $date->format('M') }}
	</span>
	@if ($showYear)
		<span class="date-block__year">
			{{ $date->format('Y') }}
		</span>
	@endif
</time>
