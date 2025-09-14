@if ($withWrapper)
	@if ($isLink)
		<a class="card-wrapper {{ $context }}">
			@opentag($tag) @class($classes) @attributes($attributes)
			<blade-fragment>
				@include('components.Card.partials.card-image')
				@include('components.Card.partials.card-body')
			</blade-fragment>
			@closetag($tag)
		</a>
	@else
		<div class="card-wrapper {{ $context }}">
			@opentag($tag) @class($classes) @attributes($attributes)>
			<blade-fragment>
				@include('components.Card.partials.card-image')
				@include('components.Card.partials.card-body')
			</blade-fragment>
			@closetag($tag)
		</div>
	@endif
@else
	{{-- If it's not a div, use both the selected tag and an <a> --}}
	@if ($isLink && $tag !== 'div')
		@opentag($tag) @class($classes) @attributes($attributes)
		<a class="{{ $shortName }}__link {{ $bemName }}__link" @attributes($linkAttrs)>
			@include('components.Card.partials.card-image')
			@include('components.Card.partials.card-body')
		</a>
		@closetag($tag)
		{{-- If it's a div and a link, just swap out the div for an <a> instead of using an extra wrapping element --}}
	@elseif($isLink && $tag === 'div')
		<a @class($classes) @attributes([...$attributes, ...$linkAttrs])>
			@include('components.Card.partials.card-image')
			@include('components.Card.partials.card-body')
		</a>
		{{-- If it's not a link, just use the selected tag --}}
	@else
		@opentag($tag) @class($classes) @attributes($attributes)>
		<blade-fragment>
			@include('components.Card.partials.card-image')
			@include('components.Card.partials.card-body')
		</blade-fragment>
		@closetag($tag)
	@endif
@endif
