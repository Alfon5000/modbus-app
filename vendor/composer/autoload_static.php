<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita796672ab1be3a5d576cb5474b9434a1
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'ModbusTcpClient\\' => 16,
        ),
        'A' => 
        array (
            'Alfonso\\Modbus\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ModbusTcpClient\\' => 
        array (
            0 => __DIR__ . '/..' . '/aldas/modbus-tcp-client/src',
        ),
        'Alfonso\\Modbus\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita796672ab1be3a5d576cb5474b9434a1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita796672ab1be3a5d576cb5474b9434a1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita796672ab1be3a5d576cb5474b9434a1::$classMap;

        }, null, ClassLoader::class);
    }
}
