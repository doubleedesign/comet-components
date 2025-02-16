<?php
use Doubleedesign\Comet\Core\Columns;
use Doubleedesign\Comet\Core\Column;
use Doubleedesign\Comet\Core\Paragraph;

$columnContent = [new Paragraph([], 'Column')];

$attributes = [];
$innerComponents = [
	(new Column(['backgroundColor' => 'light'], $columnContent)),
	(new Column(['backgroundColor' => 'light'], $columnContent))
];

if(isset($_REQUEST['hAlign'])) {
	$attributes['justifyContent'] = $_REQUEST['hAlign'];
}
if(isset($_REQUEST['vAlign'])) {
	$attributes['alignItems'] = $_REQUEST['vAlign'];
}

if(isset($_REQUEST['widths'])) {
	$innerComponents = [];
	$widths = explode(',', $_REQUEST['widths']);
	foreach($widths as $width) {
		if(empty($width)) {
			array_push($innerComponents, (new Column(['backgroundColor' => 'light'], $columnContent)));
		}
		else {
			array_push($innerComponents, (new Column(['width' => $width, 'backgroundColor' => 'light'], $columnContent)));
		}
	}

	// If the qty is higher than the number of specified widths, fill in the rest with unspecified widths
	if(isset($_REQUEST['qty']) && count($innerComponents) < $_REQUEST['qty']) {
		array_push($innerComponents, (new Column(['backgroundColor' => 'light'], $columnContent)));
	}
}
else if(isset($_REQUEST['qty'])) {
	for($i = 3; $i <= $_REQUEST['qty']; $i++) {
		array_push($innerComponents, (new Column(['backgroundColor' => 'light'], $columnContent)));
	}
}

$component = new Columns($attributes, $innerComponents);
$component->render();
