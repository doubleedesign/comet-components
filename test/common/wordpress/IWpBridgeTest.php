<?php
namespace Doubleedesign\Comet\TestUtils\WordPress;

interface IWpBridgeTest {

    /**
     * Which blocks need to be available for this test case/suite
     * @param array $blockNames
     * @return void
     */
    function register_blocks(array $blockNames): void;
}
