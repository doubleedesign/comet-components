<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\Tests;
use Doubleedesign\Comet\Components\BaseAttributes;
use PHPUnit\Framework\TestCase;

class BaseAttributesTest extends TestCase {

    public function test_kebab_case_id_kept_intact() {
        $attrs = ['id' => 'test-id'];
        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertEquals('id="test-id"', trim($html_attrs));
    }

    public function test_pascal_case_id_kept_intact() {
        $attrs = ['id' => 'TestID'];
        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertEquals('id="TestID"', trim($html_attrs));
    }

    public function test_snake_case_id_kept_intact() {
        $attrs = ['id' => 'test_id'];
        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertEquals('id="test_id"', trim($html_attrs));
    }

    public function test_id_with_spaces_to_kebab_case() {
        $attrs = ['id' => 'test id'];
        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertEquals('id="test-id"', trim($html_attrs));
    }

    public function test_valid_className_attribute() {
        $attrs = ['className' => 'test-class'];
        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertEquals('class="test-class"', trim($html_attrs));
    }

    public function test_empty_or_null_attributes_are_omitted_from_output() {
        $attrs = [
            'id'        => '',
            'data-test' => null,
            'className' => 'test-class'
        ];

        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertStringContainsString('test-class', $html_attrs);
        $this->assertStringNotContainsString('id=""', $html_attrs);
        $this->assertStringNotContainsString('data-test', $html_attrs);
    }

    public function test_empty_attributes() {
        $attributes = new BaseAttributes([]);

        $this->assertEquals('div', $attributes->get_tag());
        $this->assertStringNotContainsString('class="', $attributes->get_html_attributes());
        $this->assertStringNotContainsString('style="', $attributes->get_html_attributes());
    }

    public function test_ignore_invalid_alignment() {
        $attrs = [
            'align'     => 'invalid-alignment',
            'textAlign' => 'not-valid'
        ];

        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertStringNotContainsString('text-align:', $html_attrs);
    }

    public function test_filter_out_default_style_class() {
        $attrs = [
            'className' => 'test-class is-style-default another-class'
        ];

        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertEquals('class="test-class another-class"', trim($html_attrs));
    }

    public function test_data_attributes_are_preserved() {
        $attrs = [
            'data-test'  => 'value',
        ];

        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertEquals('data-test="value"', trim($html_attrs));
    }

    public function test_valid_aria_attributes_are_preserved() {
        $attrs = [
            'aria-label' => 'Test Label'
        ];

        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertEquals('aria-label="Test Label"', trim($html_attrs));
    }

    public function test_invalid_aria_attributes_are_filtered_out() {
        $attrs = [
            'aria-nothing' => 'true'
        ];

        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertStringNotContainsString('aria-invalid', $html_attrs);
    }

    public function test_invalid_for_element_aria_attributes_are_filtered_out() {
        $attrs = [
            'tagName' => 'code',
            'aria-haspopup' => 'true'
        ];

        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertStringNotContainsString('aria-invalid', $html_attrs);
    }

    public function test_special_characters_are_escaped() {
        $attrs = [
            'data-test' => 'value with "quotes" & <special> characters',
        ];

        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertEquals('data-test="value with &amp;quot;quotes&amp;quot; &amp;amp; &amp;lt;special&amp;gt; characters"', trim($html_attrs));
    }

    public function test_text_align_styles() {
        $alignments = [
            'left'    => 'style="text-align: left;"',
            'center'  => 'style="text-align: center;"',
            'right'   => 'style="text-align: right;"',
        ];

        foreach ($alignments as $align => $expected_style) {
            $attrs = ['textAlign' => $align];
            $attributes = new BaseAttributes($attrs);
            $html_attrs = $attributes->get_html_attributes();

            $this->assertEquals($expected_style, trim($html_attrs));
        }
    }

    public function test_handling_of_nested_arrays() {
        $attrs = [
            'className'  => 'test-class',
            'layout'     => ['type' => 'flex'],
            'focalPoint' => ['x' => 0.5, 'y' => 0.5]
        ];

        $attributes = new BaseAttributes($attrs);
        $html_attrs = $attributes->get_html_attributes();

        $this->assertStringContainsString('test-class', $html_attrs);
        $this->assertStringNotContainsString('layout', $html_attrs);
        $this->assertStringNotContainsString('focalPoint', $html_attrs);
    }
}
