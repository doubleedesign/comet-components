<?php
namespace Doubleedesign\Comet\Components;

interface IRenderable {
	function get_inline_styles(): string;
	function get_html_attributes(): string;
    public function render(): void;
}
