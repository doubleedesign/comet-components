<?php
namespace Doubleedesign\Comet\Core;

/**
 * DateBlockEvent component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display a summary of an event including title, date, location and links.
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class EventCard extends UIComponent {
	/**
	 * @var string $title
	 * @description The name of the event.
	 */
	protected string $title;
	/**
	 * @var string|null $detailUrl
	 * @description The URL to be used for the detail page.
	 */
	protected ?string $detailUrl;
	/**
	 * @var array|null $externalLink
	 * @description An associative array with fields URL, label, and target for an external link to a separate ticketing, registration, or other third-party site.
	 */
	protected ?array $externalLink = [];
	/**
	 * @var string|null $location
	 * @description The location of the event, such as the name of the venue or an address.
	 */
	protected ?string $location;

	function __construct(array $attributes) {
		parent::__construct($attributes, 'components.EventCard.event-card');
		$this->detailUrl = $attributes['detailUrl'] ?? null;
		$this->externalLink = $attributes['externalLink'] ?? null;
		$this->location = $attributes['location'] ?? null;
	}

	#[NotImplemented]
	function render(): void {
		// Check the render method of the parent and see if it needs to be overridden,
		// if not then remove this method and the annotation
	}
}
