<?php

use Doubleedesign\Comet\TestUtils\PestUtils;
use Doubleedesign\Comet\Core\{ListComponent, ListItem};

describe('List component', function() {
    it('renders an unordered list by default', function() {
        $list = new ListComponent([], [new ListItem([], 'Item 1'), new ListItem([], 'Item 2')]);
        ob_start();
        $list->render();
        $output = ob_get_clean();

        expect($output)->toContain('<ul');
    });

    it('renders an unordered list when the "ordered" attribute is false', function() {
        $list = new ListComponent(['ordered' => false], [new ListItem([], 'Item 1'), new ListItem([], 'Item 2')]);
        ob_start();
        $list->render();
        $output = ob_get_clean();

        expect($output)->toContain('<ul');
    });

    it('renders an ordered list when the "ordered" attribute is true', function() {
        $list = new ListComponent(['ordered' => true], [new ListItem([], 'Item 1'), new ListItem([], 'Item 2')]);
        ob_start();
        $list->render();
        $output = ob_get_clean();

        expect($output)->toContain('<ol');
    });

    it('has the expected default HTML and BEM class structure', function() {
        $list = new ListComponent([], [new ListItem([], 'Item 1'), new ListItem([], 'Item 2')]);

        expect($list->get_bem_classes())->toEqual(['list']);

        ob_start();
        $list->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'ul.list',
            'li.list__item'
        ]);
    });

    it('has the expected BEM class structure when context is specified', function() {
        $list = new ListComponent(['context' => 'some-content'], [new ListItem([], 'Item 1'), new ListItem([], 'Item 2')]);

        expect($list->get_bem_classes())->toEqual(['some-content__list']);

        ob_start();
        $list->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'ul.some-content__list',
            'li.some-content__list__item'
        ]);
    });

    it('appends "-list" to a shortname that does not already end with "list"', function() {
        $list = new ListComponent(['shortName' => 'membership'], [new ListItem([], 'Item 1'), new ListItem([], 'Item 2')]);

        expect($list->get_bem_classes())->toEqual(['membership-list']);

        ob_start();
        $list->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $list = $dom->getElementsByTagName('ul')->item(0);

        expect($list->getAttribute('class'))->toEqual('membership-list');
    });

    it('has the expected BEM class structure when shortName is specified but no context', function() {
        $list = new ListComponent(['shortName' => 'memberships'], [new ListItem([], 'Item 1'), new ListItem([], 'Item 2')]);

        expect($list->get_bem_classes())->toEqual(['memberships-list']);

        ob_start();
        $list->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'ul.memberships-list',
            'li.memberships-list__item'
        ]);
    });

    it('has the expected BEM class structure when context AND shortName are specified', function() {
        $list = new ListComponent(['context' => 'site-footer', 'shortName' => 'memberships'], [new ListItem([], 'Item 1'), new ListItem([], 'Item 2')]);

        expect($list->get_bem_classes())->toEqual(['site-footer__memberships-list']);

        ob_start();
        $list->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'ul.site-footer__memberships-list',
            'li.site-footer__memberships-list__item'
        ]);
    });
});
