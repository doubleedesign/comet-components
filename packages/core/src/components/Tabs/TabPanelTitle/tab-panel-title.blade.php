<li @if($classes)@class($classes)@endif @attributes($attributes)>
	<a href="#{{ $anchor }}" role="tab">{!! $content !!}</a>
</li>
