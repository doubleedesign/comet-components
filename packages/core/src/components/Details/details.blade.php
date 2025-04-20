<details @class($classes) @attributes($attributes)>
	<summary class="details__summary">
		{{ $summary }}
	</summary>
	<div class="details__content">
		@foreach ($children as $child)
			@if (method_exists($child, 'render'))
				{{ $child->render() }}
			@endif
		@endforeach
	</div>
</details>
