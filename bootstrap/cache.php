<?php

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;

return function (Application $app) {
    // Configuración de caché forzada
    $app->instance('config', $config = new \Illuminate\Config\Repository([
        'cache' => [
            'default' => 'array',
            'stores' => [
                'array' => [
                    'driver' => 'array',
                    'serialize' => false,
                ],
            ],
        ],
    ]));

    // Registrar el gestor de caché
    $app->singleton('cache', function ($app) {
        return new CacheManager($app);
    });

    $app->singleton('cache.store', function ($app) {
        return $app['cache']->driver();
    });

    // Configurar el sistema de archivos
    $app->instance('files', new Filesystem());

    return $app;
};
