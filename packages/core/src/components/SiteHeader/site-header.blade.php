@if ($breakpoint === null)
    <header @if ($classes) @class($classes) @endif @attributes($attributes)>
        @foreach ($children as $child)
            @if (method_exists($child, 'render'))
                {{ $child->render() }}
            @endif
        @endforeach
    </header>
@else
    <header @if ($classes) @class($classes) @endif @attributes($attributes)>
        <div class="container" @attributes($containerAttributes)>
            @foreach ($persistentComponentsStart as $child)
                @if (method_exists($child, 'render'))
                    {{ $child->render() }}
                @endif
            @endforeach
            <div data-vue-component="site-header__responsive"
                class="site-header__responsive site-header__responsive--{{ $responsiveStyle }}">
                <site-header-responsive breakpoint="{{ $breakpoint }}" menu-data="{{ $responsiveMenuData }}"
                    toggle-button-icon-prefix="{{ $toggleButtonIconPrefix }}"
                    toggle-button-icon-class="{{ $toggleButtonIconClass }}" menu-html="{{ $menuComponentHtml }}"
                    submenu-toggle-icon-class="{{ $submenuToggleIconClass }}"
                    responsive-start-html="{{ $responsiveComponentsStart }}"
                    responsive-end-html="{{ $responsiveComponentsEnd }}">
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
