<?php
use Doubleedesign\Comet\Core\{ResponsivePanels, ResponsivePanel, Heading, Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['colorTheme', 'breakpoint', 'iconPrefix', 'icon', 'orientation'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$component = new ResponsivePanels(
    $attributes,
    [
        new ResponsivePanel(
            [
                'title'    => 'Panel 1',
                'subtitle' => 'Subtitle 1',
            ],
            [
                new Heading([], 'The cushions are the essence of the chair!'),
                new Paragraph([], "Come on, Ross, you're a paleontologist. Dig a little deeper. Just to be clear, comedy with the plates will not be well-received. Okay, well, who identified this restaurant's tone as \"pretentious-comma-garlicky\"?"),
                new Paragraph([], "Wow. I haven't seen you this worked-up since you did that dog-food commercial and you thought you were gonna be with a real talking dog. Yeah, that was a disappointment If you want to receive emails about my upcoming shows, please give me money so I can buy a computer. We said aloof, not a doof! You don't have a TV? What's all your furniture pointed at?")
            ]
        ),
        new ResponsivePanel(
            [
                'title'    => 'Panel 2',
                'subtitle' => 'Subtitle 2',
            ],
            [
                new Paragraph([], 'This is the content of panel 2')
            ]
        ),
        new ResponsivePanel(
            [
                'title' => 'Panel 3'
            ],
            [
                new Paragraph([], 'This is the content of panel 3')
            ]
        ),
        new ResponsivePanel(
            [
                'title'    => 'Panel 4',
                'subtitle' => 'Subtitle 4',
            ],
            [
                new Paragraph([], 'This is the content of panel 4')
            ]
        ),
    ]);

$component->render();
