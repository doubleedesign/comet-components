<?php
use Doubleedesign\Comet\Core\{ResponsivePanels,
	ResponsivePanel,
	ResponsivePanelTitle,
	ResponsivePanelContent,
	Paragraph
};

$component = new ResponsivePanels(
	[
		'colorTheme' => 'primary',
	],
	[
		new ResponsivePanel(
			[],
			[
				new ResponsivePanelTitle([], 'Panel 1'),
				new ResponsivePanelContent([], [
					new Paragraph([], 'This is the content of panel 1')
				])
			]
		),
		new ResponsivePanel(
			[],
			[
				new ResponsivePanelTitle([], 'Panel 2'),
				new ResponsivePanelContent([], [
					new Paragraph([], 'This is the content of panel 2')
				])
			]
		),
		new ResponsivePanel(
			[],
			[
				new ResponsivePanelTitle([], 'Panel 3'),
				new ResponsivePanelContent([], [
					new Paragraph([], 'This is the content of panel 3')
				])
			]
		)
	]);

$component->render();

