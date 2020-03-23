<?php
// Route::any('admin', function() {
//     return redirect()->route('admin.login');
// })->name('login');

Route::group(['middleware' => ['web','https_protocol']], function () {

    // auth
    Route::get('login', config('kulara.controllers.auth.login') . '@loginForm')->name('admin.login');
    Route::post('login', config('kulara.controllers.auth.login') . '@login')->name('admin.login');
    Route::any('logout', config('kulara.controllers.auth.login') . '@logout')->name('admin.logout');
    Route::get('profile', config('kulara.controllers.auth.profile') . '@updateForm')->name('admin.profile');
    Route::patch('profile', config('kulara.controllers.auth.profile') . '@update');
    Route::get('password/change', config('kulara.controllers.auth.change_password') . '@changeForm')->name('admin.password.change');
    Route::patch('password/change', config('kulara.controllers.auth.change_password') . '@change');
    Route::get('password/reset', config('kulara.controllers.auth.forgot_password') . '@emailForm')->name('admin.password.request');
    Route::post('password/email', config('kulara.controllers.auth.forgot_password') . '@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token?}', config('kulara.controllers.auth.reset_password') . '@resetForm')->name('admin.password.reset');
    Route::post('password/reset', config('kulara.controllers.auth.reset_password') . '@reset')->name('admin.password.update');

});

Route::group(['middleware' => ['web','https_protocol'],'prefix' => config('kulara.route_prefix','admin')], function () {
    // backend
    Route::get('/', config('kulara.controllers.backend') . '@index')->name('admin');
    Route::get('/locale/{locale}', config('kulara.controllers.backend') . '@locale')->name('admin.locale');
    Route::get('dashboard', config('kulara.controllers.backend') . '@dashboard')->name('admin.dashboard');
    Route::get('settings', config('kulara.controllers.backend') . '@settingsForm')->name('admin.settings');
    Route::patch('settings', config('kulara.controllers.backend') . '@settings');
    Route::post('summernote/image/upload', config('kulara.controllers.backend') . '@summernoteImageUpload')->name('admin.summernote.imageUpload');
    Route::get('logs', config('kulara.controllers.backend').'@view_logs')->name('admin.log');
    Route::get('test', config('kulara.controllers.backend').'@test')->name('admin.test');

    // role
    Route::get('roles', config('kulara.controllers.role') . '@index')->name('admin.roles');
    Route::get('roles/create', config('kulara.controllers.role') . '@createForm')->name('admin.roles.create');
    Route::post('roles/create', config('kulara.controllers.role') . '@create');
    Route::get('roles/read/{id}', config('kulara.controllers.role') . '@read')->name('admin.roles.read');
    Route::get('roles/update/{id}', config('kulara.controllers.role') . '@updateForm')->name('admin.roles.update');
    Route::patch('roles/update/{id}', config('kulara.controllers.role') . '@update');
    Route::delete('roles/delete/{id}', config('kulara.controllers.role') . '@delete')->name('admin.roles.delete');

    // user
    Route::get('users', config('kulara.controllers.user') . '@index')->name('admin.users');
    Route::get('users/create', config('kulara.controllers.user') . '@createForm')->name('admin.users.create');
    Route::post('users/create', config('kulara.controllers.user') . '@create');
    Route::get('users/read/{id}', config('kulara.controllers.user') . '@read')->name('admin.users.read');
    Route::get('users/update/{id}', config('kulara.controllers.user') . '@updateForm')->name('admin.users.update');
    Route::patch('users/update/{id}', config('kulara.controllers.user') . '@update');
    Route::get('users/password/{id}', config('kulara.controllers.user') . '@passwordForm')->name('admin.users.password');
    Route::patch('users/password/{id}', config('kulara.controllers.user') . '@password');
    Route::delete('users/delete/{id}', config('kulara.controllers.user') . '@delete')->name('admin.users.delete');

    // activity_logs
    Route::get('activity_logs', config('kulara.controllers.activity_log') . '@index')->name('admin.activity_logs');
    Route::get('activity_logs/read/{id}', config('kulara.controllers.activity_log') . '@read')->name('admin.activity_logs.read');

    // docs
    Route::get('docs', config('kulara.controllers.doc') . '@index')->name('admin.docs');
    Route::get('docs/create', config('kulara.controllers.doc') . '@createForm')->name('admin.docs.create');
    Route::post('docs/create', config('kulara.controllers.doc') . '@create');
    Route::get('docs/read/{id}', config('kulara.controllers.doc') . '@read')->name('admin.docs.read');
    Route::get('docs/update/{id}', config('kulara.controllers.doc') . '@updateForm')->name('admin.docs.update');
    Route::patch('docs/update/{id}', config('kulara.controllers.doc') . '@update');
    Route::patch('docs/move/{id}', config('kulara.controllers.doc') . '@move')->name('admin.docs.move');
    Route::delete('docs/delete/{id}', config('kulara.controllers.doc') . '@delete')->name('admin.docs.delete');

    // settings
    Route::get('settings', config('kulara.controllers.setting') . '@index')->name('admin.settings');
    Route::get('settings/create', config('kulara.controllers.setting') . '@createForm')->name('admin.settings.create');
    Route::post('settings/create', config('kulara.controllers.setting') . '@create');
    Route::get('settings/read/{setting}', config('kulara.controllers.setting') . '@read')->name('admin.settings.read');
    Route::get('settings/update/{setting}', config('kulara.controllers.setting') . '@updateForm')->name('admin.settings.update');
    Route::patch('settings/update/{setting}', config('kulara.controllers.setting') . '@update');
    Route::delete('settings/delete/{setting}', config('kulara.controllers.setting') . '@delete')->name('admin.settings.delete');

    // permissions
    Route::get('permissions', config('kulara.controllers.permission') . '@index')->name('admin.permissions');
    Route::get('permissions/create', config('kulara.controllers.permission') . '@createForm')->name('admin.permissions.create');
    Route::post('permissions/create', config('kulara.controllers.permission') . '@create');
    Route::get('permissions/read/{permission}', config('kulara.controllers.permission') . '@read')->name('admin.permissions.read');
    Route::get('permissions/update/{permission}', config('kulara.controllers.permission') . '@updateForm')->name('admin.permissions.update');
    Route::patch('permissions/update/{permission}', config('kulara.controllers.permission') . '@update');
    Route::delete('permissions/delete/{permission}', config('kulara.controllers.permission') . '@delete')->name('admin.permissions.delete');

    // seotools
    Route::get('seotools', config('kulara.controllers.seotool') . '@index')->name('admin.seotools');
    Route::get('seotools/create/{model_id}/{model_name}', config('kulara.controllers.seotool') . '@createForm')->name('admin.seotools.create');
    Route::post('seotools/create/{model_id}/{model_name}', config('kulara.controllers.seotool') . '@create');
    Route::get('seotools/read/{seotool}', config('kulara.controllers.seotool') . '@read')->name('admin.seotools.read');
    Route::get('seotools/update/{seotool}', config('kulara.controllers.seotool') . '@updateForm')->name('admin.seotools.update');
    Route::patch('seotools/update/{seotool}', config('kulara.controllers.seotool') . '@update');
    Route::delete('seotools/delete/{seotool}', config('kulara.controllers.seotool') . '@delete')->name('admin.seotools.delete');
});

Route::get('docs/{id?}/{slug?}', config('kulara.controllers.doc') . '@frontend')->name('docs');
