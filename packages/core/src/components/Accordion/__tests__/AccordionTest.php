<?php
use Doubleedesign\Comet\Core\{
    Accordion,
    AccordionPanel,
    AccordionPanelContent,
    AccordionPanelTitle,
    Paragraph
};

beforeEach(function() {
    $this->panels = [
        new AccordionPanel([], [
            new AccordionPanelTitle([], 'Panel 1 title'),
            new AccordionPanelContent([], [new Paragraph([], 'Panel 1 content')])
        ]),
    ];
});

test('bem class structure', function() {
    ob_start();
    $component = new Accordion([], $this->panels);
    $component->render();
    $output = ob_get_clean();

    $dom = new DOMDocument();
    @$dom->loadHTML($output);
    $wrapper = $dom->getElementsByTagName('div')->item(0);
    $panel = $wrapper->getElementsByTagName('details')->item(0);
    $title = $panel->getElementsByTagName('summary')->item(0);
    $content = $panel->getElementsByTagName('div')->item(0);

    expect($wrapper->getAttribute('class'))->toEqual('accordion');
    expect($panel->getAttribute('class'))->toEqual('accordion__panel');
    expect($title->getAttribute('class'))->toEqual('accordion__panel__title');
    expect($content->getAttribute('class'))->toEqual('accordion__panel__content');
});

test('colour theme attribute', function() {
    ob_start();
    $component = new Accordion(['colorTheme' => 'accent'], $this->panels);
    $component->render();
    $output = ob_get_clean();

    $dom = new DOMDocument();
    @$dom->loadHTML($output);
    $wrapper = $dom->getElementsByTagName('div')->item(0);

    expect($wrapper->getAttribute('data-color-theme'))->toEqual('accent');
});
