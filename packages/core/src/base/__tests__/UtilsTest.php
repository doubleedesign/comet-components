<?php

describe('Utils', function() {
    describe('kebab_case', function() {
        it('handles PascalCase', function() {
            $result = Doubleedesign\Comet\Core\Utils::kebab_case('PascalCaseExample');
            expect($result)->toBe('pascal-case-example');
        });

        it('handles namespaced strings', function() {
            $result = Doubleedesign\Comet\Core\Utils::kebab_case('Namespace/BlockName');
            expect($result)->toBe('namespace-block-name');
        });

        it('handles double underscores', function() {
            $result = Doubleedesign\Comet\Core\Utils::kebab_case('block__modifier');
            expect($result)->toBe('block-modifier');
        });
    });

    describe('PascalCase', function() {
        it('converts a string to PascalCase', function() {
            $result = Doubleedesign\Comet\Core\Utils::pascal_case('test-string');
            expect($result)->toBe('TestString');
        });

        it('handles underscores and hyphens', function() {
            $result = Doubleedesign\Comet\Core\Utils::pascal_case('test_string-example');
            expect($result)->toBe('TestStringExample');
        });
    });
});
