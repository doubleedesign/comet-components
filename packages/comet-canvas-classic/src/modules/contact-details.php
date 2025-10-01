<?php
/** @var $fields array */
use Doubleedesign\Comet\Core\Container;
use Doubleedesign\Comet\WordPress\Classic\PreprocessedHTML;

ob_start();
get_template_part('template-parts/contact-details');
$content = ob_get_clean();

if ($fields['isNested']) {
    $component = new PreprocessedHTML([], $content);
}
else {
    $component = new Container(
        [
            'context'     => 'contact-details-wrapper',
        ],
        [new PreprocessedHTML([], $content)]
    );
}

$component->render();
