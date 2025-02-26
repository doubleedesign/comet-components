<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::A])]
#[DefaultTag(Tag::A)]
class Link extends Renderable {
	protected string $content;
	/**
	 * @var ?string $iconPrefix
	 * @description Icon prefix class name.
	 */
	protected ?string $iconPrefix = null;
	/**
	 * @var ?string $icon
	 * @description Icon class name. If not set, defaults to one matching the file type.
	 */
	protected ?string $icon = null;

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, 'components.Link.link');
		$this->content = $content;
		$this->context = $attributes['context'] ?? null;

		if(isset($attributes['iconPrefix']) || isset($attributes['icon']) || $this->context === 'link-group') {
			$this->iconPrefix = $attributes['iconPrefix'] ?? 'fa-solid';
		}
		if (isset($attributes['icon'])) {
			$this->icon = $attributes['icon']; // TODO: Sanitisation/validation of icon class name
		}
		else if($this->context === 'link-group') {
			if (isset($attributes['target']) && $attributes['target'] === '_blank') {
				$this->icon = 'fa-arrow-up-right-from-square';
			}
			else {
				$this->icon = 'fa-link';
			}
		}
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'iconPrefix' => $this->iconPrefix,
			'icon'       => $this->icon,
			'content'    => $this->content
		])->render();
	}
}
