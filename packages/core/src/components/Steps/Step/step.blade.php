<li @class($classes) @attributes($attributes)>
	<div @if ($innerClasses) @class($innerClasses) @endif>
		<blade-fragment>
			@include('components._blade-partials.children')
		</blade-fragment>
	</div>
</li>
