@if ($breakpoint === null)
	<header @class($classes) @attributes($attributes)>
		@include('components._blade-partials.children')
	</header>
@else
	<header @class($classes) @attributes($attributes)>
		<div class="site-header__container" @attributes($containerAttributes)>
			@foreach ($persistentComponentsStart as $child)
				@if (method_exists($child, 'render'))
					{{ $child->render() }}
				@endif
			@endforeach
			<div data-vue-component="site-header__responsive" class="site-header__responsive site-header__responsive--{{ $responsiveStyle }}">
				<site-header-responsive breakpoint="{{ $breakpoint }}"
				                        menu-data="{{ $responsiveMenuData }}"
				                        toggle-button-icon-prefix="{{ $toggleButtonIconPrefix }}"
				                        overlay-background="{{ $overlayBackgroundColor }}"
										toggle-button-icon-class="{{ $toggleButtonIconClass }}"
										menu-html="{{ $menuComponentHtml }}"
										submenu-toggle-icon-class="{{ $submenuToggleIconClass }}"
										responsive-start-html="{{ $responsiveComponentsStart }}"
										responsive-end-html="{{ $responsiveComponentsEnd }}"
				>
				</site-header-responsive>
			</div>
			@foreach ($persistentComponentsEnd as $child)
				@if (method_exists($child, 'render'))
					{{ $child->render() }}
				@endif
			@endforeach
		</div>
	</header>
@endif
