<?php
namespace Doubleedesign\Comet\Core;

/**
 * SiteHeader component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a site header with a logo, responsive menu, and optional additional components.
 */
#[AllowedTags([Tag::HEADER])]
#[DefaultTag(Tag::HEADER)]
class SiteHeader extends LayoutComponent {
    use BackgroundColor;
    use Icon;
    use LayoutContainerSize;

    /**
     * @var ?string $breakpoint
     * @description Viewport breakpoint (in pixels or rem) at which to switch from the "mobile" style menu to "desktop" style menu;
     *              use 0 to always use the "mobile" style or null to always use the "desktop" style
     *              Note: "Desktop" style will still have a responsive layout, but submenus won't have the preferred behaviour on small viewports
     *              e.g., phones - they'll appear on hover like on desktop, not in accordion style that responds better to touch interactions.
     */
    protected ?string $breakpoint = null;

    /**
     * @var string|null $logoUrl
     * @description The URL of the site logo image
     */
    protected ?string $logoUrl = null;

    /**
     * @var ?string $icon
     * @description Icon class name; for the responsive menu toggle button
     * @default-value fa-bars
     */
    protected ?string $icon;

    /**
     * @var string|null $submenuIcon
     * @description Icon class name for the submenu toggle button in responsive mode
     * @default-value fa-chevron-down
     */
    protected ?string $submenuIcon = 'fa-chevron-down';

    /**
     * @var string|null $style
     * @description The layout style of the responsive menu when below the breakpoint
     * @supported-values basic, overlay, off-canvas
     */
    protected ?string $responsiveStyle = 'overlay';

    /**
     * @var ThemeColor|null $overlayBackgroundColor
     * @description Background colour of the overlay when responsiveStyle is set to 'overlay'
     * @default-value ThemeColor::PRIMARY
     */
    protected ?ThemeColor $overlayBackgroundColor = ThemeColor::DARK;
    private array $menuData = [];
    private ?Menu $menuComponent;
    private array $alwaysShowComponents = [];
    private array $showInOverlaysComponents = [];

    /**
     * @var array $componentGroups
     * @description The inner components other than the logo, provided in groups based on when and where they should be shown.
     *              Valid keys are: 'menuComponent', 'alwaysShow', 'showInOverlays'
     */
    protected array $componentGroups;

    public function __construct(array $attributes, array $componentGroups) {
        parent::__construct($attributes, [], 'components.SiteHeader.site-header');
        $this->set_size($attributes);
        $this->set_background_color($attributes);
        $this->set_overlay_background_color_from_attrs($attributes);
        $this->set_icon_from_attrs($attributes, 'fa-bars');
        $this->breakpoint = $attributes['breakpoint'] ?? $this->breakpoint;
        $this->responsiveStyle = $attributes['responsiveStyle'] ?? $this->responsiveStyle;
        $this->submenuIcon = $attributes['submenuIcon'] ?? $this->submenuIcon;
        $this->logoUrl = $attributes['logoUrl'] ?? null;

        $logo = isset($this->logoUrl)
            ? new ContentImageBasic([
                'context'   => $this->get_shortname(),
                'shortName' => 'logo',
                'src'       => $this->logoUrl,
                'alt'       => 'Site logo',
                'href'      => '/',
            ])
            : null;

        $wrappedAlwaysShow = (isset($componentGroups['alwaysShow']) && !empty($componentGroups['alwaysShow'])) ? new Group(
            [
                'context'   => $this->get_bem_prefix(),
                'shortName' => 'top'
            ],
            $componentGroups['alwaysShow']
        ) : null;

        $this->alwaysShowComponents = isset($logo) ? [$logo] : [];
        $this->alwaysShowComponents = isset($wrappedAlwaysShow) ? array_merge([$wrappedAlwaysShow], $this->alwaysShowComponents) : $this->alwaysShowComponents;
        $this->menuComponent = $componentGroups['menuComponent'] ?? null;
        $this->showInOverlaysComponents = $componentGroups['showInOverlays'] ?? [];

        if (isset($this->menuComponent)) {
            $this->menuComponent->update_context($this->get_bem_prefix());
            $this->menuData = $this->menuComponent->get_raw_menu_data(null);
        }

        // Save the final group configuration to this instance
        $this->componentGroups = [
            'menuComponent'  => $this->menuComponent,
            'alwaysShow'     => $this->alwaysShowComponents,
            'showInOverlays' => $this->showInOverlaysComponents
        ];
    }

    protected function set_overlay_background_color_from_attrs(array $attributes): void {
        if (isset($attributes['overlayBackgroundColor'])) {
            if ($attributes['overlayBackgroundColor'] instanceof ThemeColor) {
                $this->overlayBackgroundColor = $attributes['overlayBackgroundColor'];
            }
            else {
                $this->overlayBackgroundColor = ThemeColor::tryFrom($attributes['overlayBackgroundColor']) ?? $this->overlayBackgroundColor;
            }
        }
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->size)) {
            $attributes['data-size'] = $this->size->value;
        }

        if (isset($this->backgroundColor)) {
            $attributes['data-background'] = $this->backgroundColor->value;
        }

        return $attributes;
    }

    protected function get_inline_styles(): array {
        $styles = parent::get_inline_styles();

        $styles['--breakpoint'] = $this->breakpoint ?? 'none';

        return $styles;
    }

    protected function get_prerendered_html(array $components): string {
        ob_start();
        foreach ($components as $component) {
			if($component !== null) {
				$component->render();
			}
        }

        return ob_get_clean() ?? '';
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        // Always "desktop" / basic mode - no Vue for responsive rendering and touch-friendly menu behaviour
        // TODO: Should probably fix the "touch-friendly" part using media queries and maybe some basic JS - would only really affect menus with submenus
        if ($this->breakpoint === null || $this->responsiveStyle === 'basic') {
            echo $blade->make($this->bladeFile, [
                'breakpoint' => null,
                'classes'    => $this->get_filtered_classes(),
                'attributes' => $this->get_html_attributes(),
                'children'   => isset($this->menuComponent) ? array_merge($this->alwaysShowComponents, [$this->menuComponent]) : $this->alwaysShowComponents
            ])->render();
        }
        else {
            echo $blade->make($this->bladeFile, [
                'children'                    => $this->alwaysShowComponents,
                'classes'                     => $this->get_filtered_classes(),
                'attributes'                  => $this->get_html_attributes(),
                'breakpoint'                  => $this->breakpoint,
                'responsiveStyle'             => $this->responsiveStyle,
                'overlayBackgroundColor'      => $this->overlayBackgroundColor->value,
                'toggleButtonIconPrefix'      => $this->iconPrefix,
                'toggleButtonIconClass'       => $this->icon,
                'submenuToggleIconClass'      => $this->submenuIcon,
                // Menu data For Vue to transform in below-breakpoint mode
                'responsiveMenuData'          => json_encode($this->menuData),
                // Default menu HTML for when we don't need Vue to do anything except render it
                // (This allows rendering in the DOM only when required, preventing duplicate menus in the HTML)
                'menuComponentHtml'              => isset($this->menuComponent) ? $this->get_prerendered_html([$this->menuComponent]) : '',
                'extraContentHtml'               => $this->get_prerendered_html($this->showInOverlaysComponents)
            ])->render();
        }
    }
}
