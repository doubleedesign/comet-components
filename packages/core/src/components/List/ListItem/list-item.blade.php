<li @class($classes) @attributes($attributes)>
	@if (!empty($content))
		{!! $content !!}
	@endif
	@if (!empty($children))
		<blade-fragment>
			@include('components._blade-partials.children')
		</blade-fragment>
	@endif
</li>
