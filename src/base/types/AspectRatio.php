<?php
namespace Doubleedesign\Comet\Components;

enum AspectRatio: string {
	case STANDARD = '4:3';
	case PORTAIT = '3:4';
	case SQUARE = '1:1';
	case WIDE = '16:9';
	case TALL = '9:16';
	case CLASSIC = '3:2';
	case CLASSIC_PORTRAIT = '2:3';
}
