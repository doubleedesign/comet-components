<?php
namespace Doubleedesign\Comet\Core;

enum ContainerSize: string {

    // These should match the container breakpoints used in the CSS
    // and don't necessarily align to the actively expected/supported widths of all elements that use this (though we probably should support them all)
    case FULLWIDTH = 'fullwidth';
    case WIDE = 'wide';

    case NARROW = 'narrow';
    case NARROWER = 'narrower';
    case SMALL = 'small';
    case DEFAULT = 'contained';
}
