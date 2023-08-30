<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc7c5d3f244ceb7ee360fe2ac1c6639a4
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'EventPrime\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'EventPrime\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc7c5d3f244ceb7ee360fe2ac1c6639a4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc7c5d3f244ceb7ee360fe2ac1c6639a4::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc7c5d3f244ceb7ee360fe2ac1c6639a4::$classMap;

        }, null, ClassLoader::class);
    }
}
