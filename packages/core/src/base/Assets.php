<?php
namespace Doubleedesign\Comet\Core;

class Assets {
	private static ?Assets $instance = null;

	protected array $globalStyles = [];
	protected array $globalScripts = [];
	protected array $styles = [];
	protected array $scripts = [];
	private ?string $domain = null;

	// Private constructor to prevent direct instantiation
	private function __construct() {
		$env = getEnv();
		$origin = $env['HTTP_ORIGIN'] ?? null;
		$isStorybookLocal = $origin && str_contains($origin, 'storybook.comet-components.test:6006');
		if($isStorybookLocal) {
			$this->domain = 'https://comet-components.test'; // TODO: Get this from .env
		}
		else {
			$referrer = $_SERVER['HTTP_REFERER'] ?? null;
			$isStorybookProd = $referrer && str_contains($referrer, 'storybook.cometcomponents.io');
			if($isStorybookProd) {
				$this->domain = 'https://cometcomponents.io';
			}
		}

		// Add the global CSS
		$srcDir = dirname(__FILE__, 2);
		$absolute_path = $srcDir . '/components/global.css';
		$this->add_global_stylesheet($absolute_path);
	}

	// Singleton pattern to get the instance of the class in things that want to use it
	public static function get_instance(): self {
		if(self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function normalize_path($path): string {
		return str_replace('\\', '/', $path);
	}

	/**
	 * Transform a filesystem path to just the package bit
	 * OR if we are coming from Storybook, the package bit preceded by the main project domain
	 * @param $absolute_path
	 * @return string
	 */
	private function get_relative_or_domain_path($absolute_path): string {
		// If the path contains 'vendor', split before that and return the second part
		if(str_contains($absolute_path, 'vendor')) {
			$parts = explode('vendor', $absolute_path);
			if(count($parts) < 2) {
				return $absolute_path;
			}
			$path = '/vendor' . $parts[1];
		}
		// Otherwise, this is probably the dev environment, so go from /packages
		else {
			$parts = explode('packages', $absolute_path);
			if(count($parts) < 2) {
				return $absolute_path;
			}
			$path = '/packages' . $parts[1];
		}

		$normalizedPath = $this->normalize_path($path);
		if($this->domain) {
			$normalizedPath = $this->domain . $normalizedPath;
		}

		return $normalizedPath;
	}

	/**
	 * Add a global stylesheet to the asset loader
	 * @param string $absolute_path - filesystem path to the stylesheet (e.g. C:/Users/path/to/project/packages/core/src/components/global.css)
	 * @return void
	 */
	public function add_global_stylesheet(string $absolute_path): void {
		$path = $this->get_relative_or_domain_path($absolute_path);
		if(!in_array($path, $this->globalStyles)) {
			$this->globalStyles[] = $path;
		}
	}

	/**
	 * Add a global script to the asset loader
	 * @param string $absolute_path - filesystem path to the script (e.g. C:/Users/path/to/file)
	 * @return void
	 */
	public function add_global_script(string $absolute_path): void {
		$path = $this->get_relative_or_domain_path($absolute_path);
		if(!in_array($path, $this->globalScripts)) {
			$this->globalScripts[] = $path;
		}
	}

	/**
	 * Add a component stylesheet to the asset loader
	 * @param string $absolute_path - filesystem path to the stylesheet (e.g. C:/Users/path/to/file)
	 * @return void
	 */
	public function add_stylesheet(string $absolute_path): void {
		$path = $this->get_relative_or_domain_path($absolute_path);
		if(!in_array($path, $this->styles)) {
			$this->styles[] = $path;
		}
	}

	/**
	 * Add a component script to the asset loader
	 * @param string $absolute_path - filesystem path to the script (e.g. C:/Users/path/to/file)
	 * @param array $attributes - HTML attributes to add to the script tag, such as type="module"
	 * @return void
	 */
	public function add_script(string $absolute_path, array $attributes): void {
		$path = $this->get_relative_or_domain_path($absolute_path);
		// Prevent duplication
		// TODO: This can be replaced with array_any() when we can use PHP 8.4
		foreach($this->scripts as $script) {
			if($script['path'] === $path) {
				return;
			}
		}
		$this->scripts[] = [
			'path'       => $path,
			'attributes' => $attributes,
		];
	}

	/**
	 * Render the global stylesheets as HTML <link> tags
	 * This is suitable for use in the <head> of a document
	 * @return void
	 */
	public function render_global_stylesheet_html(): void {
		$styles = array_map(function($style) {
			return '<link rel="stylesheet" href="' . $style . '">';
		}, $this->globalStyles);

		echo implode(PHP_EOL, $styles) . PHP_EOL;
	}

	/**
	 * Render the component stylesheets as HTML <link> tags
	 * IMPORTANT: This needs to be run after components have been instantiated, otherwise their stylesheets won't be added in time
	 * @return void
	 */
	public function render_component_stylesheet_html(): void {
		$styles = array_map(function($style) {
			return '<link rel="stylesheet" href="' . $style . '">';
		}, $this->styles);

		echo implode(PHP_EOL, $styles) . PHP_EOL;
	}

	/**
	 * Render the global scripts as HTML script tags
	 * This is suitable for use in the <head> of a document, or at the end of the <body>
	 * but if component scripts rely on them, you should ensure these are rendered first
	 * @return void
	 */
	public function render_global_script_html(): void {
		$scripts = array_map(function($script) {
			return '<script src="' . $script . '"></script>';
		}, $this->globalScripts);

		echo implode(PHP_EOL, $scripts) . PHP_EOL;
	}

	/**
	 * Render the component scripts as HTML script tags
	 * IMPORTANT: This needs to be run after components have been instantiated, otherwise their scripts won't be added in time
	 * Suitable for use at the end of the document, just before the closing <body> tag
	 * and should be run after the global scripts if they rely on any of those
	 * @return void
	 */
	public function render_component_script_html(): void {
		$scripts = array_map(function($item) {
			$attributes = $item['attributes'];

			$attributeString = array_map(function($key, $value) {
				return $key . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
			}, array_keys($attributes), $attributes);

			return sprintf(
				'<script src="%s?v=%s" %s></script>',
				htmlspecialchars($item['path'], ENT_QUOTES, 'UTF-8'),
				time(), // v=time() to bust the cache and ensure Vue components reload when request params change
				implode(' ', $attributeString)
			);
		}, $this->scripts);

		echo implode(PHP_EOL, $scripts) . PHP_EOL;
	}
}
