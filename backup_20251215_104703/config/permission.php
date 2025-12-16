<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Permission Configuration
    |--------------------------------------------------------------------------
    |
    | Minimal configuration to allow using spatie/laravel-permission in this
    | test project. Run `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
    | to publish the full configuration when the package is installed.
    |
    */

    'models' => [
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'cache' => [
        'expiration_time' => 24 * 60,
    ],
];
