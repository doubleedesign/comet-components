<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class TabPanel extends UIComponent {
	/** @var array<TabPanelTitle|TabPanelContent> */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'tabs__content']),
			$innerComponents,
			'components.Tabs.TabPanel.tab-panel'
		);
	}

	public function get_title(): ?array {
		// TODO: Can potentially use array_find when we can use PHP 8.4
		foreach($this->innerComponents as $component) {
			if($component instanceof TabPanelTitle) {
				ob_start();
				$component->render();
				$content = ob_get_clean();

				// Strip the wrapping <span> that WordPress sends while keeping any other inline HTML intact
				$content = preg_replace('/<span>\s*(.*?)\s*<\/span>/s', '$1', $content);

				return array(
					'attributes' => $component->get_html_attributes(),
					'classes'    => $component->get_filtered_classes(),
					'content'    => trim($content)
				);
			}
		}

		return null;
	}

	public function get_content(): ?array {
		// TODO: Can potentially use array_find when we can use PHP 8.4
		foreach($this->innerComponents as $component) {
			if($component instanceof TabPanelContent) {
				ob_start();
				$component->render();
				$content = ob_get_clean();

				return array(
					'attributes' => $component->get_html_attributes(),
					'classes'    => $component->get_filtered_classes(),
					'content'    => trim($content),
				);
			}
		}

		return null;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		// Render the children directly without wrapping this component with its own tag,
		// if this even gets used - generally it is expected that get_title() and get_content() will be used by the parent component
		// to pass the children directly to its Vue component
		echo $blade->make($this->bladeFile, [
			'children' => $this->innerComponents
		])->render();
	}
}
