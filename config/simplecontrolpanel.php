<?php

return [
    'route_prefix' => 'admin',
    'widgets_namespace' => '\App\Widgets',
    'widgets_path' => 'app/Widgets',
    'controllers' => [
        'auth' => [
            'change_password' => '\Khludev\KuLaraPanel\Controllers\Auth\ChangePasswordController',
            'forgot_password' => '\Khludev\KuLaraPanel\Controllers\Auth\ForgotPasswordController',
            'login' => '\Khludev\KuLaraPanel\Controllers\Auth\LoginController',
            'profile' => '\Khludev\KuLaraPanel\Controllers\Auth\ProfileController',
            'reset_password' => '\Khludev\KuLaraPanel\Controllers\Auth\ResetPasswordController',
        ],
        'activity_log' => '\Khludev\KuLaraPanel\Controllers\ActivityLogController',
        'backend' => '\Khludev\KuLaraPanel\Controllers\BackendController',
        'doc' => '\Khludev\KuLaraPanel\Controllers\DocController',
        'role' => '\Khludev\KuLaraPanel\Controllers\RoleController',
        'permission' => '\Khludev\KuLaraPanel\Controllers\PermissionController',
        'setting' => '\Khludev\KuLaraPanel\Controllers\SettingController',
        'user' => '\Khludev\KuLaraPanel\Controllers\UserController',
        'seotool' => '\Khludev\KuLaraPanel\Controllers\SeotoolController',
        'api' => '\Khludev\KuLaraPanel\Controllers\ApiController',
    ],

    // models used by package
    'models' => [
        'activity_log' => '\Khludev\KuLaraPanel\Models\ActivityLog',
        'doc' => '\Khludev\KuLaraPanel\Models\Doc',
        'permission' => '\Khludev\KuLaraPanel\Models\Permission',
        'role' => '\Khludev\KuLaraPanel\Models\Role',
        'setting' => '\Khludev\KuLaraPanel\Models\Setting',
        'user' => '\Khludev\KuLaraPanel\Models\User',
        'seotool' => '\Khludev\KuLaraPanel\Models\Seotool',
    ],

    'crud_paths' => [
        'stubs' => 'vendor/wikichua/simplecontrolpanel/resources/stubs/crud/default',
        'controller' => 'app/Http/Controllers/Admin',
        'model' => 'app',
        'migrations' => 'database/migrations',
        'views' => 'resources/views/admin',
        'menu' => 'resources/views/vendor/kulara/layouts/menu',
        'layout_menu' => 'resources/views/vendor/kulara/layouts/menu.blade.php',
        'route' => 'routes/admin',
        'routes' => 'routes/web.php',
    ],

    'modules' => [
        'Admin Panel',
        'Roles',
        'Users',
        'Activity Logs',
        'Docs',
        'Settings',
    ],

];
