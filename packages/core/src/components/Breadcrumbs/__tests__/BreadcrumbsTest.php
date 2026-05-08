<?php

use Doubleedesign\Comet\Core\Breadcrumbs;
use Doubleedesign\Comet\TestUtils\PestUtils;

describe('Breadcrumbs', function() {

    it('has the default expected BEM class structure', function() {
        $breadcrumbs = new Breadcrumbs([], [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'About', 'url' => '/about', 'current' => true],
            ['title' => 'Team', 'url' => '/about/team']
        ]);

        ob_start();
        $breadcrumbs->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body, 6);

        expect($hierarchy)->toEqual([
            'nav.breadcrumbs',
            'ol.breadcrumbs__list',
            'li.breadcrumbs__list__item',
            'a.breadcrumbs__list__item__link',
            'span.breadcrumbs__list__item__link__label',
        ]);
    });

    it('adds aria-current to the current item', function() {
        $breadcrumbs = new Breadcrumbs([], [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'About', 'url' => '/about', 'current' => true],
            ['title' => 'Team', 'url' => '/about/team']
        ]);

        ob_start();
        $breadcrumbs->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $links = $dom->getElementsByTagName('a');

        expect($links->length)->toBe(3)
            ->and($links->item(0)->getAttribute('aria-current'))->toBe('')
            ->and($links->item(1)->getAttribute('aria-current'))->toEqual('page')
            ->and($links->item(2)->getAttribute('aria-current'))->toBe('');
    });
});
