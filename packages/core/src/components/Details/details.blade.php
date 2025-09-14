<details @class($classes) @attributes($attributes)>
	<summary class="details__summary">
		{{ $summary }}
	</summary>
	<div class="details__content">
		@include('components._blade-partials.children')
	</div>
</details>
