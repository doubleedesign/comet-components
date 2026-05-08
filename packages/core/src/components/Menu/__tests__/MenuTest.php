<?php
/** @noinspection PhpUnhandledExceptionInspection */

use Doubleedesign\Comet\Core\Menu;
use Doubleedesign\Comet\TestUtils\PestUtils;

beforeEach(function() {
    $this->menuItems = [
        [
            'title'           => 'Home',
            'link_attributes' => ['href' => '/']
        ],
        [
            'title'           => 'About',
            'link_attributes' => ['href' => '/about'],
        ],
        [
            'title'           => 'Contact',
            'link_attributes' => ['href' => '/contact']
        ]
    ];

    $this->menuItemsWithSubmenu = [
        [
            'title'           => 'Services',
            'link_attributes' => ['href' => '/services'],
            'children'        => [
                [
                    'title'           => 'Web Development',
                    'link_attributes' => ['href' => '/services/web-development']
                ],
                [
                    'title'           => 'Graphic Design',
                    'link_attributes' => ['href' => '/services/graphic-design']
                ]
            ]
        ]
    ];
});

describe('Menu', function() {

    it('has the expected default BEM class structure (top level)', function() {
        $menu = new Menu([], $this->menuItems);

        ob_start();
        $menu->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'nav.menu',
            'ul.menu__list',
            'li.menu__list__item',
            'a.menu__list__item__link'
        ]);
    });

    it('has the expected default BEM class structure (submenu)', function() {
        $menu = new Menu([], $this->menuItemsWithSubmenu);

        ob_start();
        $menu->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('ul')->item(1);
        $hierarchy = PestUtils::getHtmlHierarchy($body, 3);

        expect($hierarchy)->toEqual([
            'ul.menu__sub-menu',
            'li.menu__sub-menu__item',
            'a.menu__sub-menu__item__link'
        ]);
    });

    it('has the expected BEM class structure when context is provided (top level)', function() {
        $menu = new Menu(['context' => 'site-footer'], $this->menuItems);

        ob_start();
        $menu->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'nav.site-footer__menu',
            'ul.site-footer__menu__list',
            'li.site-footer__menu__list__item',
            'a.site-footer__menu__list__item__link'
        ]);
    });

    it('has the expected BEM class structure when context is provided (submenu)', function() {
        $menu = new Menu(['context' => 'site-footer'], $this->menuItemsWithSubmenu);

        ob_start();
        $menu->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('ul')->item(1);
        $hierarchy = PestUtils::getHtmlHierarchy($body, 3);

        expect($hierarchy)->toEqual([
            'ul.site-footer__menu__sub-menu',
            'li.site-footer__menu__sub-menu__item',
            'a.site-footer__menu__sub-menu__item__link'
        ]);
    });

    it('has the expected BEM class structure when a shortName is specified (top level)', function() {
        $menu = new Menu(['shortName' => 'main-nav'], $this->menuItems);

        ob_start();
        $menu->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'nav.main-nav',
            'ul.main-nav__list',
            'li.main-nav__list__item',
            'a.main-nav__list__item__link'
        ]);
    });

    it('has the expected BEM class structure when a shortName is specified (submenu)', function() {
        $menu = new Menu(['shortName' => 'main-nav'], $this->menuItems);

        ob_start();
        $menu->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('ul')->item(1);
        $hierarchy = PestUtils::getHtmlHierarchy($body, 3);

        expect($hierarchy)->toEqual([
            'ul.menu__sub-menu',
            'li.menu__sub-menu__item',
            'a.menu__sub-menu__item__link'
        ]);
    });

    it('has the expected BEM class structure when both context and shortName are specified (top level)', function() {
        $menu = new Menu(['context' => 'site-header', 'shortName' => 'nav'], $this->menuItems);

        ob_start();
        $menu->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'nav.site-header__nav',
            'ul.site-header__nav__list',
            'li.site-header__nav__list__item',
            'a.site-header__nav__list__item__link'
        ]);
    });

    it('has the expected BEM class structure when both context and shortName are specified (submenu)', function() {
        $menu = new Menu(['context' => 'site-header', 'shortName' => 'nav'], $this->menuItemsWithSubmenu);

        ob_start();
        $menu->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('ul')->item(1);
        $hierarchy = PestUtils::getHtmlHierarchy($body, 3);

        expect($hierarchy)->toEqual([
            'ul.site-header__nav__sub-menu',
            'li.site-header__nav__sub-menu__item',
            'a.site-header__nav__sub-menu__item__link'
        ]);
    });
});
