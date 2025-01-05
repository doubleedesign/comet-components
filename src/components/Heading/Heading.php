<?php
namespace Doubleedesign\Comet\Components;

class Heading extends CoreElementComponent {
	protected HeadingAttributes $attributes;
	protected ?Alignment $textAlign = null;
	/** @var string $content - plain text or basic HTML */
	protected string $content;

	function __construct(array $attributes, string $content) {
		if (isset($attributes['level'])) {
			$attributes['level'] = (int)$attributes['level'];
			$attributes['tagName'] = 'h' . $attributes['level'];
		}
		else {
			$attributes['level'] = 2;
			$attributes['tagName'] = 'h2';
		}

		// Pass the attributes as a basic array for processing of generic, common attributes within the parent
		parent::__construct($attributes, $content);
		// Cast the attributes to a more specific type for use in this class
		$this->attributes = new HeadingAttributes($attributes);
	}

	function get_html_attributes(): string {
		$baseAttributes = $this->attributes->get_filtered_attributes();
		$baseClasses = $this->attributes->get_filtered_classes();
		$styles = $this->get_inline_styles();

		$attrs = array_merge(
            $baseAttributes,
            array(
                'id' => $this->attributes->get_id(),
                'class' => implode(' ', $baseClasses),
                'style' => $styles
            )
        );

		return array_reduce(array_keys($attrs), function ($acc, $key) use ($attrs) {
			if ($attrs[$key] === null || $attrs[$key] === '') return $acc;

			return $acc . ' ' . $key . '="' . Utils::esc_attr($attrs[$key]) . '"';
		}, '');
	}

	public function render(): void {
		$output = '';
		$tag = $this->attributes->get_tag() ?? 'h2';
		$attrs = $this->get_html_attributes();

		// Add the opening tag with a space between tag and attributes if there are attributes
		$output .= "<$tag" . ($attrs ? "$attrs" : "") . ">";

		if ($this->content) {
			// Strip out HTML tags except inline inner ones
			// (there's some assumptions here about the content that will be received here)
			$allowed_tags = '<a><em><strong><span><i><sup><sub><small>'; // TODO: There are more tags that should be allowed?
			$text = strip_tags($this->content, $allowed_tags);

			//$output .= wp_kses_post($text); // TODO: Vanilla equivalent of wp_kses_post
			$output .= $text;
		}

		$output .= "</$tag>";

		echo $output;
	}
}

class HeadingAttributes extends CoreAttributes {
	protected ?HeadingTag $tag = null;

	public function __construct(array $attrs) {
		parent::__construct($attrs);
		$this->tag = isset($attrs['tagName']) ? HeadingTag::tryFrom($attrs['tagName']) : HeadingTag::H2;
	}

	public function get_tag(): ?string {
		return $this->tag->value;
	}
}
