<?php
use Doubleedesign\Comet\Core\{Config, Container};
use function Patchwork\{redefine, restoreAll};

beforeEach(function() {
    Config::init();
});

afterEach(function() {
	restoreAll();
});

test('same bg as global is ignored if container is not nested', function() {
    redefine('Doubleedesign\Comet\Core\Config::get_global_background', fn() => 'dark');

    ob_start();
    $component = new Container(['backgroundColor' => 'dark', 'isNested' => false], []);
    $component->render();
    $output = ob_get_clean();

    $dom = new DOMDocument();
    @$dom->loadHTML($output);
    $container = $dom->getElementsByTagName('section')->item(0);

    expect($container->hasAttribute('data-background'))->toBeFalse();
});
