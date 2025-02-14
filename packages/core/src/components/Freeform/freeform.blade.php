<div @if($classes)@class($classes)@endif @attributes($attributes)>
	<!-- TODO Check if this is the right way to pass the raw HTML for security (letting Blade sanitise rather than preprocessing) -->
	{{ $content }}
</div>
