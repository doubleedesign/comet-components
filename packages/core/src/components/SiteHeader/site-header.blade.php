<header @class($classes) @attributes($attributes)>
	{{-- Always-shown components, which should include the logo--}}
	{{-- This should also include the menu if always in desktop mode or responsiveMode=basic --}}
	@include('components._blade-partials.children')
	{{-- Non-basic responsive mode - Vue used for responsive rendering of the menu and optional extras --}}
	@if($breakpoint !== null)
		<div data-vue-component="site-header__responsive">
			<site-header-responsive breakpoint="{{ $breakpoint }}"
			                        responsive-style="{{ $responsiveStyle }}"
			                        menu-data="{{ $responsiveMenuData }}"
			                        toggle-button-icon-prefix="{{ $toggleButtonIconPrefix }}"
			                        overlay-background="{{ $overlayBackgroundColor }}"
									toggle-button-icon-class="{{ $toggleButtonIconClass }}"
									menu-html="{{ $menuComponentHtml }}"
									submenu-toggle-icon-class="{{ $submenuToggleIconClass }}"
			                        extra-content-html="{{ $extraContentHtml }}"
			>
			</site-header-responsive>
		</div>
	@endif
</header>
