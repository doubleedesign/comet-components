<?php

use Doubleedesign\Comet\Core\PostNav;
use Doubleedesign\Comet\TestUtils\PestUtils;

describe('PostNav', function() {

    it('has the default expected BEM class structure', function() {
        $instance = new PostNav([
            'links' => [
                ['title' => 'Previous', 'url' => '/previous-post'],
                ['title' => 'Next', 'url' => '/next-post']
            ]
        ]);

        ob_start();
        $instance->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body, 3);

        expect($hierarchy)->toEqual([
            'nav.post-nav',
            'a.post-nav__link post-nav__link--prev'
        ]);
    });

    it('labels the links with the default entity name', function() {
        $instance = new PostNav([
            'links' => [
                ['title' => 'Previous', 'url' => '/previous-post'],
                ['title' => 'Next', 'url' => '/next-post']
            ]
        ]);

        ob_start();
        $instance->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $links = $dom->getElementsByTagName('a');

        expect($links->length)->toBe(2)
            ->and($links->item(0)->textContent)->toContain('Previous Post')
            ->and($links->item(1)->textContent)->toContain('Next Post');
    });

    it('labels the links with a custom entity name if provided', function() {
        $instance = new PostNav([
            'entityName' => 'Article',
            'links'      => [
                ['title' => 'Previous', 'url' => '/previous-article'],
                ['title' => 'Next', 'url' => '/next-article']
            ]
        ]);

        ob_start();
        $instance->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $links = $dom->getElementsByTagName('a');

        expect($links->length)->toBe(2)
            ->and($links->item(0)->textContent)->toContain('Previous Article')
            ->and($links->item(1)->textContent)->toContain('Next Article');
    });
});
