<?php
// Route::any('admin', function() {
//     return redirect()->route('admin.login');
// })->name('login');

Route::group(['middleware' => ['web','https_protocol']], function () {

    // auth
    Route::get('login', config('asimshazad.controllers.auth.login') . '@loginForm')->name('admin.login');
    Route::post('login', config('asimshazad.controllers.auth.login') . '@login')->name('admin.login');
    Route::any('logout', config('asimshazad.controllers.auth.login') . '@logout')->name('admin.logout');
    Route::get('profile', config('asimshazad.controllers.auth.profile') . '@updateForm')->name('admin.profile');
    Route::patch('profile', config('asimshazad.controllers.auth.profile') . '@update');
    Route::get('password/change', config('asimshazad.controllers.auth.change_password') . '@changeForm')->name('admin.password.change');
    Route::patch('password/change', config('asimshazad.controllers.auth.change_password') . '@change');
    Route::get('password/reset', config('asimshazad.controllers.auth.forgot_password') . '@emailForm')->name('admin.password.request');
    Route::post('password/email', config('asimshazad.controllers.auth.forgot_password') . '@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token?}', config('asimshazad.controllers.auth.reset_password') . '@resetForm')->name('admin.password.reset');
    Route::post('password/reset', config('asimshazad.controllers.auth.reset_password') . '@reset')->name('admin.password.update');

});

Route::group(['middleware' => ['web','https_protocol'],'prefix' => config('asimshazad.route_prefix','admin')], function () {
    // backend
    Route::get('/', config('asimshazad.controllers.backend') . '@index')->name('admin');
    Route::get('/locale/{locale}', config('asimshazad.controllers.backend') . '@locale')->name('admin.locale');
    Route::get('dashboard', config('asimshazad.controllers.backend') . '@dashboard')->name('admin.dashboard');
    Route::get('settings', config('asimshazad.controllers.backend') . '@settingsForm')->name('admin.settings');
    Route::patch('settings', config('asimshazad.controllers.backend') . '@settings');
    Route::post('summernote/image/upload', config('asimshazad.controllers.backend') . '@summernoteImageUpload')->name('admin.summernote.imageUpload');
    Route::get('logs', config('asimshazad.controllers.backend').'@view_logs')->name('admin.log');
    Route::get('test', config('asimshazad.controllers.backend').'@test')->name('admin.test');

    // role
    Route::get('roles', config('asimshazad.controllers.role') . '@index')->name('admin.roles');
    Route::get('roles/create', config('asimshazad.controllers.role') . '@createForm')->name('admin.roles.create');
    Route::post('roles/create', config('asimshazad.controllers.role') . '@create');
    Route::get('roles/read/{id}', config('asimshazad.controllers.role') . '@read')->name('admin.roles.read');
    Route::get('roles/update/{id}', config('asimshazad.controllers.role') . '@updateForm')->name('admin.roles.update');
    Route::patch('roles/update/{id}', config('asimshazad.controllers.role') . '@update');
    Route::delete('roles/delete/{id}', config('asimshazad.controllers.role') . '@delete')->name('admin.roles.delete');

    // user
    Route::get('users', config('asimshazad.controllers.user') . '@index')->name('admin.users');
    Route::get('users/create', config('asimshazad.controllers.user') . '@createForm')->name('admin.users.create');
    Route::post('users/create', config('asimshazad.controllers.user') . '@create');
    Route::get('users/read/{id}', config('asimshazad.controllers.user') . '@read')->name('admin.users.read');
    Route::get('users/update/{id}', config('asimshazad.controllers.user') . '@updateForm')->name('admin.users.update');
    Route::patch('users/update/{id}', config('asimshazad.controllers.user') . '@update');
    Route::get('users/password/{id}', config('asimshazad.controllers.user') . '@passwordForm')->name('admin.users.password');
    Route::patch('users/password/{id}', config('asimshazad.controllers.user') . '@password');
    Route::delete('users/delete/{id}', config('asimshazad.controllers.user') . '@delete')->name('admin.users.delete');

    // activity_logs
    Route::get('activity_logs', config('asimshazad.controllers.activity_log') . '@index')->name('admin.activity_logs');
    Route::get('activity_logs/read/{id}', config('asimshazad.controllers.activity_log') . '@read')->name('admin.activity_logs.read');

    // docs
    Route::get('docs', config('asimshazad.controllers.doc') . '@index')->name('admin.docs');
    Route::get('docs/create', config('asimshazad.controllers.doc') . '@createForm')->name('admin.docs.create');
    Route::post('docs/create', config('asimshazad.controllers.doc') . '@create');
    Route::get('docs/read/{id}', config('asimshazad.controllers.doc') . '@read')->name('admin.docs.read');
    Route::get('docs/update/{id}', config('asimshazad.controllers.doc') . '@updateForm')->name('admin.docs.update');
    Route::patch('docs/update/{id}', config('asimshazad.controllers.doc') . '@update');
    Route::patch('docs/move/{id}', config('asimshazad.controllers.doc') . '@move')->name('admin.docs.move');
    Route::delete('docs/delete/{id}', config('asimshazad.controllers.doc') . '@delete')->name('admin.docs.delete');

    // settings
    Route::get('settings', config('asimshazad.controllers.setting') . '@index')->name('admin.settings');
    Route::get('settings/create', config('asimshazad.controllers.setting') . '@createForm')->name('admin.settings.create');
    Route::post('settings/create', config('asimshazad.controllers.setting') . '@create');
    Route::get('settings/read/{setting}', config('asimshazad.controllers.setting') . '@read')->name('admin.settings.read');
    Route::get('settings/update/{setting}', config('asimshazad.controllers.setting') . '@updateForm')->name('admin.settings.update');
    Route::patch('settings/update/{setting}', config('asimshazad.controllers.setting') . '@update');
    Route::delete('settings/delete/{setting}', config('asimshazad.controllers.setting') . '@delete')->name('admin.settings.delete');

    // permissions
    Route::get('permissions', config('asimshazad.controllers.permission') . '@index')->name('admin.permissions');
    Route::get('permissions/create', config('asimshazad.controllers.permission') . '@createForm')->name('admin.permissions.create');
    Route::post('permissions/create', config('asimshazad.controllers.permission') . '@create');
    Route::get('permissions/read/{permission}', config('asimshazad.controllers.permission') . '@read')->name('admin.permissions.read');
    Route::get('permissions/update/{permission}', config('asimshazad.controllers.permission') . '@updateForm')->name('admin.permissions.update');
    Route::patch('permissions/update/{permission}', config('asimshazad.controllers.permission') . '@update');
    Route::delete('permissions/delete/{permission}', config('asimshazad.controllers.permission') . '@delete')->name('admin.permissions.delete');

    // seotools
    Route::get('seotools', config('asimshazad.controllers.seotool') . '@index')->name('admin.seotools');
    Route::get('seotools/create/{model_id}/{model_name}', config('asimshazad.controllers.seotool') . '@createForm')->name('admin.seotools.create');
    Route::post('seotools/create/{model_id}/{model_name}', config('asimshazad.controllers.seotool') . '@create');
    Route::get('seotools/read/{seotool}', config('asimshazad.controllers.seotool') . '@read')->name('admin.seotools.read');
    Route::get('seotools/update/{seotool}', config('asimshazad.controllers.seotool') . '@updateForm')->name('admin.seotools.update');
    Route::patch('seotools/update/{seotool}', config('asimshazad.controllers.seotool') . '@update');
    Route::delete('seotools/delete/{seotool}', config('asimshazad.controllers.seotool') . '@delete')->name('admin.seotools.delete');

    //Gallery
    Route::post('media/update-attrs', '\App\Http\Controllers\Controller' . '@saveImgAttributes');
});

Route::get('docs/{id?}/{slug?}', config('asimshazad.controllers.doc') . '@frontend')->name('docs');
