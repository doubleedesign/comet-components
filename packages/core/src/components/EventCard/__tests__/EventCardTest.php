<?php
use Doubleedesign\Comet\TestUtils\PestUtils;
use Doubleedesign\Comet\Core\EventCard;

describe('EventCard', function() {
    it('has the expected default HTML and BEM class structure', function() {
        $eventCard = new EventCard(['name' => 'Sample Event']);

        expect($eventCard->get_bem_classes())->toEqual(['event-card']);

        ob_start();
        $eventCard->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'div.event-card',
            'div.event-card__content',
            'h3'
        ]);
    });
});
