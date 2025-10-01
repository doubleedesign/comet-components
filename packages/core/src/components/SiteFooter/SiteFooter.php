<?php
namespace Doubleedesign\Comet\Core;

/**
 * SiteFooter component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a footer with inner components such as a Menu.
 */
#[AllowedTags([Tag::FOOTER])]
#[DefaultTag(Tag::FOOTER)]
class SiteFooter extends WrappedLayoutComponent {

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.SiteFooter.site-footer');
    }
}
