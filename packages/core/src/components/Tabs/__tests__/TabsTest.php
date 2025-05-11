<?php
/** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\Core\{Paragraph, TabPanel, TabPanelContent, TabPanelTitle, Tabs};

beforeEach(function() {
    $this->panels = [
        new TabPanel([], [
            new TabPanelTitle([], 'Panel 1 title'),
            new TabPanelContent([], [new Paragraph([], 'Panel 1 content')])
        ]),
    ];
});
test('bem class structure', function() {
    ob_start();
    $component = new Tabs([], $this->panels);
    $component->render();
    $output = ob_get_clean();

    $dom = new DOMDocument();
    @$dom->loadHTML($output);
    $wrapper = $dom->getElementsByTagName('div')->item(0);
    $tabList = $wrapper->getElementsByTagName('ul')->item(0);
    $tabListItem = $tabList->getElementsByTagName('li')->item(0);
    $tabContentWrapper = $wrapper->getElementsByTagName('div')->item(0);
    $tabContentItem = $tabContentWrapper->getElementsByTagName('div')->item(0);

    expect($wrapper->getAttribute('class'))->toEqual('tabs');
    expect($tabList->getAttribute('class'))->toEqual('tabs__tab-list');
    expect($tabListItem->getAttribute('class'))->toEqual('tabs__tab-list__item');
    expect(explode(' ', $tabContentWrapper->getAttribute('class')))->toContain('tabs__content');
    expect($tabContentItem->getAttribute('class'))->toEqual('tabs__content__tab-panel');
});
test('role attributes', function() {
    ob_start();
    $component = new Tabs([], $this->panels);
    $component->render();
    $output = ob_get_clean();

    $dom = new DOMDocument();
    @$dom->loadHTML($output);
    $wrapper = $dom->getElementsByTagName('div')->item(0);
    $tabList = $wrapper->getElementsByTagName('ul')->item(0);
    $tabListItemLink = $tabList->getElementsByTagName('a')->item(0);
    $tabContentWrapper = $wrapper->getElementsByTagName('div')->item(0);
    $tabContentItem = $tabContentWrapper->getElementsByTagName('div')->item(0);

    expect($tabList->getAttribute('role'))->toEqual('tablist');
    expect($tabListItemLink->getAttribute('role'))->toEqual('tab');
    expect($tabContentItem->getAttribute('role'))->toEqual('tabpanel');
});
test('orientation attribute', function() {
    ob_start();
    $component = new Tabs(['orientation' => 'vertical'], $this->panels);
    $component->render();
    $output = ob_get_clean();

    $dom = new DOMDocument();
    @$dom->loadHTML($output);
    $wrapper = $dom->getElementsByTagName('div')->item(0);

    expect($wrapper->getAttribute('data-orientation'))->toEqual('vertical');
});
test('colour theme attribute', function() {
    ob_start();
    $component = new Tabs(['colorTheme' => 'secondary'], $this->panels);
    $component->render();
    $output = ob_get_clean();

    $dom = new DOMDocument();
    @$dom->loadHTML($output);
    $wrapper = $dom->getElementsByTagName('div')->item(0);
    $content = $wrapper->getElementsByTagName('div')->item(0);

    expect($content->getAttribute('data-color-theme'))->toEqual('secondary');
});
