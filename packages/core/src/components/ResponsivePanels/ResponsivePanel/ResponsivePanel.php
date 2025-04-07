<?php
namespace Doubleedesign\Comet\Core;

// Note: This component's tag changes responsively,
// determined by the Vue component that renders within ResponsivePanels
#[AllowedTags([Tag::DIV, Tag::DETAILS])]
#[DefaultTag(Tag::DIV)]
class ResponsivePanel extends UIComponent {
	/**
	 * This component should contain a title and content
	 * and exists only as an underlying structural mechanism without directly rendering its own tag to the front-end
	 * (it may *appear( to have one when rendered in the Vue component - that's determined by the Vue component, not this class)
	 * @var array<ResponsivePanelTitle|ResponsivePanelContent> $innerComponents
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ResponsivePanels.ResponsivePanel.responsive-panel');
	}

	public function get_title(): ?array {
		// TODO: Can potentially use array_find when we can use PHP 8.4
		foreach($this->innerComponents as $component) {
			if($component instanceof ResponsivePanelTitle) {
				ob_start();
				$component->render();
				$content = ob_get_clean();

				// Strip the wrapping <span> that WordPress sends while keeping any other inline HTML intact
				$content = preg_replace('/<span>\s*(.*?)\s*<\/span>/s', '$1', $content);

				// Get the separate elements rather than the rendered result
				// so that the ResponsivePanels Vue component can add/modify them cleanly
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
			if($component instanceof ResponsivePanelContent) {
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
