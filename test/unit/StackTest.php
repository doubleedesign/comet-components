<?php

namespace Doubleedesign\Comet\Tests;

use Doubleedesign\Comet\Components\Stack;
use Doubleedesign\Comet\TestUtils\WordPress\WpBridgeTestCase;

class StackTest extends WpBridgeTestCase {

    private function wpIntegrationTestCases_match(): array {
        $defaultLayout = ['type' => 'flex', 'orientation' => 'vertical'];

        return [
//            'Default'      => array('layout' => ['type' => 'flex', 'orientation' => 'vertical']),
            'Align top'     => [['layout' => array_merge($defaultLayout, ['verticalAlignment' => 'top'])]],
            // 'Align center'  => [['layout' => ['type' => 'flex', 'orientation' => 'vertical', 'verticalAlignment' => 'center']]],
            //'Align bottom' => array('layout' => ['type' => 'flex', 'orientation' => 'vertical', 'verticalAlignment' => 'bottom']),
            'Different tag' => [['tagName' => 'section', 'layout' => $defaultLayout]],
        ];
    }

    /**
     * Test that the output of the Comet Stack component matches the default output
     * of the corresponding WordPress core block in ways that are expected to match.
     * @dataProvider wpIntegrationTestCases_match
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function test_comet_matches_wp_core($attributes): void {
        $content = array(
            array(
                'blockName'    => 'core/paragraph',
                'attrs'        => [],
                'innerHTML'    => '<p>Test content</p>',
                'innerContent' => ['<p>Test content</p>'],
                'innerBlocks'  => []
            )
        );

        ob_start();
        echo parent::$transformer->transform_block('core/group', $attributes, $content);
        $wp_output = ob_get_clean();

        ob_start();
        $comet = new Stack($attributes, $content);
        $comet->render();
        $comet_output = ob_get_clean();

        fwrite(STDOUT, "WP output:\n$wp_output\n");
        fwrite(STDOUT, "Comet output:\n$comet_output\n");

        $this->assertEquals($wp_output, $comet_output);
    }
}
