@opentag($tag) @if ($classes)
	@class($classes)
@endif @attributes($attributes)>
<span>{!! $content !!}</span>
@closetag($tag)
