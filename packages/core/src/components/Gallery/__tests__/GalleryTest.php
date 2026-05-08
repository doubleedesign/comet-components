<?php

use Doubleedesign\Comet\Core\{ContentImageBasic, Gallery};
use Doubleedesign\Comet\TestUtils\PestUtils;

describe('Gallery', function() {

    it('has the expected default HTML and BEM structure', function() {
        $gallery = new Gallery([], [
            new ContentImageBasic(['src' => 'image1.jpg', 'alt' => 'Image 1']),
            new ContentImageBasic(['src' => 'image2.jpg', 'alt' => 'Image 2']),
        ]);

        ob_start();
        $gallery->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

        expect($hierarchy)->toEqual([
            'section.gallery',
            'div.gallery__container',
            'div.gallery__images',
            'div.gallery__images__image images__image--basic',
        ]);
    });

    it('has the expected HTML structure if the specified tagName is FIGURE', function() {
        $gallery = new Gallery(['tagName' => 'figure'], [
            new ContentImageBasic(['src' => 'image1.jpg', 'alt' => 'Image 1']),
            new ContentImageBasic(['src' => 'image2.jpg', 'alt' => 'Image 2']),
        ]);

        ob_start();
        $gallery->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

        expect($hierarchy)->toEqual([
            'section.gallery',
            'div.gallery__container',
            'figure.gallery__images',
            'div.gallery__images__image images__image--basic',
        ]);
    });

    it('has the expected HTML structure if the specified tagName is SECTION', function() {
        $gallery = new Gallery(['tagName' => 'section'], [
            new ContentImageBasic(['src' => 'image1.jpg', 'alt' => 'Image 1']),
            new ContentImageBasic(['src' => 'image2.jpg', 'alt' => 'Image 2']),
        ]);

        ob_start();
        $gallery->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

        expect($hierarchy)->toEqual([
            'section.gallery',
            'div.gallery__container',
            'div.gallery__images',
            'div.gallery__images__image images__image--basic',
        ]);
    });

    it('has the expected HTML structure if the specified tagName is DIV', function() {
        $gallery = new Gallery(['tagName' => 'div'], [
            new ContentImageBasic(['src' => 'image1.jpg', 'alt' => 'Image 1']),
            new ContentImageBasic(['src' => 'image2.jpg', 'alt' => 'Image 2']),
        ]);

        ob_start();
        $gallery->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

        expect($hierarchy)->toEqual([
            'div.gallery',
            'div.gallery__container',
            'div.gallery__images',
            'div.gallery__images__image images__image--basic',
        ]);
    });
});
