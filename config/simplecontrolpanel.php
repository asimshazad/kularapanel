<?php

return [
    'route_prefix' => 'admin',
    'widgets_namespace' => '\App\Widgets',
    'widgets_path' => 'app/Widgets',
    'controllers' => [
        'auth' => [
            'change_password' => '\asimshazad\simplepanel\Controllers\Auth\ChangePasswordController',
            'forgot_password' => '\asimshazad\simplepanel\Controllers\Auth\ForgotPasswordController',
            'login' => '\asimshazad\simplepanel\Controllers\Auth\LoginController',
            'profile' => '\asimshazad\simplepanel\Controllers\Auth\ProfileController',
            'reset_password' => '\asimshazad\simplepanel\Controllers\Auth\ResetPasswordController',
        ],
        'activity_log' => '\asimshazad\simplepanel\Controllers\ActivityLogController',
        'backend' => '\asimshazad\simplepanel\Controllers\BackendController',
        'doc' => '\asimshazad\simplepanel\Controllers\DocController',
        'role' => '\asimshazad\simplepanel\Controllers\RoleController',
        'permission' => '\asimshazad\simplepanel\Controllers\PermissionController',
        'setting' => '\asimshazad\simplepanel\Controllers\SettingController',
        'user' => '\asimshazad\simplepanel\Controllers\UserController',
        'seotool' => '\asimshazad\simplepanel\Controllers\SeotoolController',
        'api' => '\asimshazad\simplepanel\Controllers\ApiController',
    ],

    // models used by package
    'models' => [
        'activity_log' => '\asimshazad\simplepanel\Models\ActivityLog',
        'doc' => '\asimshazad\simplepanel\Models\Doc',
        'permission' => '\asimshazad\simplepanel\Models\Permission',
        'role' => '\asimshazad\simplepanel\Models\Role',
        'setting' => '\asimshazad\simplepanel\Models\Setting',
        'user' => '\asimshazad\simplepanel\Models\User',
        'seotool' => '\asimshazad\simplepanel\Models\Seotool',
    ],

    'crud_paths' => [
        'stubs' => 'vendor/asimshazad/simplepanel/resources/stubs/crud/default',
        'controller' => 'app/Http/Controllers/Admin',
        'model' => 'app',
        'migrations' => 'database/migrations',
        'views' => 'resources/views/admin',
        'menu' => 'resources/views/vendor/asimshazad/layouts/menu',
        'layout_menu' => 'resources/views/vendor/asimshazad/layouts/menu.blade.php',
        'route' => 'routes/admin',
        'routes' => 'routes/web.php',
    ],
    'media' => [
        'thumb' => ''// empty or name thumb conversion
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
