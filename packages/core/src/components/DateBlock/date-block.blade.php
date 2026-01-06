<time @class($classes) @attributes($attributes)>
	@if ($showDay)
		<span class="{{$bemPrefix}}__day">
			{{ $date->format('D') }}
		</span>
	@endif
	<span class="{{$bemPrefix}}__day-number">
		{{ $date->format('d') }}
	</span>
	<span class="{{$bemPrefix}}__month">
		{{ $date->format('M') }}
	</span>
	@if ($showYear)
		<span class="{{$bemPrefix}}__year">
			{{ $date->format('Y') }}
		</span>
	@endif
</time>
