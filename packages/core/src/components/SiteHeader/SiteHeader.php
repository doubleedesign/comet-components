<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::HEADER])]
#[DefaultTag(Tag::HEADER)]
class SiteHeader extends LayoutComponent {
	use BackgroundColor;
	use LayoutContainerSize;
	use Icon;

	/**
	 * @var ?ContainerSize $size
	 * @description Keyword specifying the relative width of the container for the inner content
	 */
	protected ?ContainerSize $size = ContainerSize::DEFAULT;
	/**
	 * @var ?string $breakpoint
	 * @description Viewport breakpoint (in pixels or rem) at which to switch from the "mobile" style menu to "desktop" style menu;
	 *              use 0 to always use the "mobile" style or null to always use the "desktop" style
	 */
	protected ?string $breakpoint = null;
	/**
	 * @var string|null $logoUrl
	 * @description The URL of the site logo image
	 */
	protected ?string $logoUrl = null;
	/**
	 * @var array<Renderable> $innerComponents
	 * @description The inner components other than the logo; to define which are part of the responsive menu, wrap them in a Group with the context "responsive" (note: only one responsive group is supported)
	 */
	protected array $innerComponents;
	/**
	 * @var ?string $icon
	 * @description Icon class name; for the responsive menu toggle button
	 * @default-value fa-bars
	 */
	protected ?string $icon;
	/**
	 * @var string|null $submenuIcon
	 * @description Icon class name for the submenu toggle button in responsive mode
	 */
	protected ?string $submenuIcon = 'fa-chevron-down';
	/**
	 * @var string|null $style
	 * @description The layout style of the responsive menu
	 * @supported-values default, overlay
	 */
	protected ?string $responsiveStyle = 'default';

	/**
	 * Groups that the inner components are processed into to enable responsive behaviour while respecting the rendering order that was passed in
	 */
	private array $persistentComponentsStart;
	private array $responsiveComponentsBeforeMenu;
	private array $responsiveComponentsAfterMenu;
	private array $persistentComponentsEnd;
	private array $menuData = [];
	private Menu $menuComponent;


	function __construct(array $attributes, array $innerComponents) {
		if(!isset($attributes['icon'])) {
			$attributes['icon'] = 'fa-bars';
		}

		parent::__construct($attributes, $innerComponents, 'components.SiteHeader.site-header');
		$this->set_background_color_from_attrs($attributes);
		$this->set_size_from_attrs($attributes);
		$this->set_icon_from_attrs($attributes);
		$this->breakpoint = $attributes['breakpoint'] ?? $this->breakpoint;
		$this->responsiveStyle = $attributes['responsiveStyle'] ?? $this->responsiveStyle;
		$this->submenuIcon = $attributes['submenuIcon'] ?? $this->submenuIcon;
		$this->logoUrl = $attributes['logoUrl'] ?? null;
		$logo = isset($attributes['logoUrl'])
			? new Image([
				'src'     => $this->logoUrl,
				'alt'     => 'Site logo',
				'href'    => '/',
				'classes' => ['site-header__logo']
			])
			: null;
		$this->innerComponents = array_merge([$logo], Utils::array_flat(array_map(function($component) {
			// Unwrap the Group that's used only to define the responsive content
			if($component instanceof Group) {
				return $component->innerComponents;
			}
			else {
				return $component;
			}
		}, $innerComponents)));

		// Find any Groups with responsive context
		// note: This only checks the top level and will ignore Groups inside other components
		// if there are multiple menu components, only the first one will be treated as the main menu,
		// any others will be rendered in-place (allows for things like user account links that don't need special treatment)
		$responsiveGroupIndex = 0;
		$responsiveInnerComponents = [];
		foreach($innerComponents as $index => $component) {
			if($component instanceof Group && $component->context === 'responsive') {
				$responsiveInnerComponents = $component->innerComponents;
				$responsiveGroupIndex = $index;
			}
		}

		// Things that should be rendered before the responsive group in the HTML
		$this->persistentComponentsStart = [$logo, ...array_slice($innerComponents, 0, $responsiveGroupIndex)];
		// Things that should be rendered after the responsive group in the HTML
		$this->persistentComponentsEnd = [...array_slice($innerComponents, $responsiveGroupIndex + 1)];

		// Within the responsive group, find the first menu component,
		// handle the things before and after it, and handle transforming it into data for the Vue component
		$mainMenuIndex = 0;
		foreach($responsiveInnerComponents as $index => $innerComponent) {
			if($innerComponent instanceof Menu) {
				$mainMenuIndex = $index;
				$this->menuComponent = $innerComponent;
				$this->menuData = $innerComponent->get_raw_menu_data(null);
				break;
			}
		}

		$this->responsiveComponentsBeforeMenu = array_slice($responsiveInnerComponents, 0, $mainMenuIndex);
		$this->responsiveComponentsAfterMenu = array_slice($responsiveInnerComponents, $mainMenuIndex + 1);
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->backgroundColor)) {
			$attributes['data-background'] = $this->backgroundColor->value;
		}

		return $attributes;
	}

	protected function get_inner_container_html_attributes(): array {
		$attributes = [];

		if(isset($this->size)) {
			return ['data-size' => $this->size->value];
		}

		return $attributes;
	}

	protected function get_prerendered_html(array $components): string {
		ob_start();
		foreach($components as $component) {
			$component->render();
		}
		return ob_get_clean() ?? '';
	}

	function render(): void {
		$blade = BladeService::getInstance();

		if($this->breakpoint === null) {
			echo $blade->make($this->bladeFile, [
				'breakpoint' => null,
				'classes'    => $this->get_filtered_classes_string(),
				'attributes' => $this->get_html_attributes(),
				'children'   => [new Container(['size' => $this->size->value, 'withWrapper' => false], $this->innerComponents)]
			])->render();
		}
		else {
			echo $blade->make($this->bladeFile, [
				'containerAttributes'       => $this->get_inner_container_html_attributes(),
				'classes'                   => $this->get_filtered_classes_string(),
				'attributes'                => $this->get_html_attributes(),
				'breakpoint'                => $this->breakpoint,
				'responsiveStyle'           => $this->responsiveStyle,
				'toggleButtonIconPrefix'    => $this->iconPrefix,
				'toggleButtonIconClass'     => $this->icon,
				'submenuToggleIconClass'    => $this->submenuIcon,
				'persistentComponentsStart' => $this->persistentComponentsStart,
				'persistentComponentsEnd'   => $this->persistentComponentsEnd,
				'responsiveComponentsStart' => $this->get_prerendered_html($this->responsiveComponentsBeforeMenu),
				'responsiveComponentsEnd'   => $this->get_prerendered_html($this->responsiveComponentsAfterMenu),
				'responsiveMenuData'        => json_encode($this->menuData),
				'menuComponentHtml'         => $this->get_prerendered_html([$this->menuComponent])
			])->render();
		}
	}
}
