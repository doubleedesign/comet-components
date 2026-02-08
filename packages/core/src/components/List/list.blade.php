@if ($ordered)
	<ol @class($classes) @attributes($attributes)>
		@include('components._blade-partials.children')

	</ol>
@else
	<ul @class($classes) @attributes($attributes)>
		@include('components._blade-partials.children')

	</ul>
@endif
