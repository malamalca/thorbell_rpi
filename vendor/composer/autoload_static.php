<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb60965526bf89c4fb6e33b04b45aa818
{
    public static $files = array (
        '23c18046f52bef3eea034657bafda50f' => __DIR__ . '/..' . '/symfony/polyfill-php81/bootstrap.php',
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '253c157292f75eb38082b5acb06f3f01' => __DIR__ . '/..' . '/nikic/fast-route/src/functions.php',
        'e39a8b23c42d4e1452234d762b03835a' => __DIR__ . '/..' . '/ramsey/uuid/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Php81\\' => 23,
            'Symfony\\Polyfill\\Ctype\\' => 23,
        ),
        'R' => 
        array (
            'Ramsey\\Uuid\\' => 12,
            'Ramsey\\Collection\\' => 18,
        ),
        'P' => 
        array (
            'Pushok\\' => 7,
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
            'PiPHP\\GPIO\\' => 11,
        ),
        'J' => 
        array (
            'Jose\\Component\\Signature\\Algorithm\\' => 35,
            'Jose\\Component\\Signature\\' => 25,
            'Jose\\Component\\KeyManagement\\' => 29,
            'Jose\\Component\\Core\\' => 20,
        ),
        'F' => 
        array (
            'FastRoute\\' => 10,
            'FG\\' => 3,
        ),
        'B' => 
        array (
            'Brick\\Math\\' => 11,
            'Base64Url\\' => 10,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Php81\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php81',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Ramsey\\Uuid\\' => 
        array (
            0 => __DIR__ . '/..' . '/ramsey/uuid/src',
        ),
        'Ramsey\\Collection\\' => 
        array (
            0 => __DIR__ . '/..' . '/ramsey/collection/src',
        ),
        'Pushok\\' => 
        array (
            0 => __DIR__ . '/..' . '/edamov/pushok/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-factory/src',
            1 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'PiPHP\\GPIO\\' => 
        array (
            0 => __DIR__ . '/..' . '/piphp/gpio/src',
        ),
        'Jose\\Component\\Signature\\Algorithm\\' => 
        array (
            0 => __DIR__ . '/..' . '/web-token/jwt-signature-algorithm-ecdsa',
        ),
        'Jose\\Component\\Signature\\' => 
        array (
            0 => __DIR__ . '/..' . '/web-token/jwt-signature',
        ),
        'Jose\\Component\\KeyManagement\\' => 
        array (
            0 => __DIR__ . '/..' . '/web-token/jwt-key-mgmt',
        ),
        'Jose\\Component\\Core\\' => 
        array (
            0 => __DIR__ . '/..' . '/web-token/jwt-core',
        ),
        'FastRoute\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/fast-route/src',
        ),
        'FG\\' => 
        array (
            0 => __DIR__ . '/..' . '/fgrosse/phpasn1/lib',
        ),
        'Brick\\Math\\' => 
        array (
            0 => __DIR__ . '/..' . '/brick/math/src',
        ),
        'Base64Url\\' => 
        array (
            0 => __DIR__ . '/..' . '/spomky-labs/base64url/src',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'ReturnTypeWillChange' => __DIR__ . '/..' . '/symfony/polyfill-php81/Resources/stubs/ReturnTypeWillChange.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb60965526bf89c4fb6e33b04b45aa818::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb60965526bf89c4fb6e33b04b45aa818::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb60965526bf89c4fb6e33b04b45aa818::$classMap;

        }, null, ClassLoader::class);
    }
}
