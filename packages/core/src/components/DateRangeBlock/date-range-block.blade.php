<time @class($classes) @attributes($attributes)>
	@if ($days !== null)
		<span class="{{$bemPrefix}}__days">
			{{ $days }}
		</span>
	@endif
	<span class="{{$bemPrefix}}__day-number">
		{{ $dates }}
	</span>
	<span class="{{$bemPrefix}}__month">
		{{ $month }}
	</span>
	@if ($year !== null)
		<span class="{{$bemPrefix}}__year">
			{{ $year }}
		</span>
	@endif
</time>
