<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit587c85fae325cc4799a46f189dfa77f8
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Doubleedesign\\Comet\\WordPress\\Calendar\\' => 39,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Doubleedesign\\Comet\\WordPress\\Calendar\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Doubleedesign\\Comet\\WordPress\\Calendar\\Admin' => __DIR__ . '/../..' . '/src/Admin.php',
        'Doubleedesign\\Comet\\WordPress\\Calendar\\BlockEditorConfig' => __DIR__ . '/../..' . '/src/BlockEditorConfig.php',
        'Doubleedesign\\Comet\\WordPress\\Calendar\\Blocks' => __DIR__ . '/../..' . '/src/Blocks.php',
        'Doubleedesign\\Comet\\WordPress\\Calendar\\Events' => __DIR__ . '/../..' . '/src/Events.php',
        'Doubleedesign\\Comet\\WordPress\\Calendar\\Fields' => __DIR__ . '/../..' . '/src/Fields.php',
        'Doubleedesign\\Comet\\WordPress\\Calendar\\TemplateHandler' => __DIR__ . '/../..' . '/src/TemplateHandler.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit587c85fae325cc4799a46f189dfa77f8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit587c85fae325cc4799a46f189dfa77f8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit587c85fae325cc4799a46f189dfa77f8::$classMap;

        }, null, ClassLoader::class);
    }
}
