<?php
use Doubleedesign\Comet\Core\Container;
use function Patchwork\redefine;

test('same bg as global is ignored', function () {
    redefine('Doubleedesign\Comet\Core\CometConfig::get_global_background', fn() => 'dark');

    ob_start();
    $component = new Container(['backgroundColor' => 'dark'], []);
    $component->render();
    $output = ob_get_clean();

    $dom = new DOMDocument();
    @$dom->loadHTML($output);
    $container = $dom->getElementsByTagName('section')->item(0);

    expect($container->hasAttribute('data-background'))->toBeFalse();
});
