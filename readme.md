## Simple Control Panel

Forked from [GitHub](https://github.com/wikichua/simplecontrolpanel)

Laravel Admin Panel ("asimshazad") is a drop-in admin panel package for Laravel which promotes rapid scaffolding & development.

- [Screenshots](https://imgur.com/a/12mGWNW)
- [GitHub](https://github.com/asimshazad/simplepanel)

Features:

- More enhancement

Packages used:

- [Laravel 5.7, 5.8 & Above](https://laravel.com/)
- [Laravel Datatables](https://github.com/yajra/laravel-datatables)
- [Laravel Nestedset](https://github.com/lazychaser/laravel-nestedset)
- [Parsedown](http://parsedown.org/)
- [SEOTools](https://github.com/artesaos/seotools)
- [Log Viewer](https://github.com/rap2hpoutre/laravel-log-viewer)
- [Fast Excel](https://github.com/rap2hpoutre/fast-excel)

Assets used:

- Custom admin panel layout (inspired by [Nova](https://nova.laravel.com))
- [Bootstrap 4](https://getbootstrap.com)
- [Datatables](https://datatables.net) (with some tweaks for a better UX)
- [FontAwesome 5](https://fontawesome.com)

### Installation

Require via composer:

    composer require asimshazad/simplepanel master-dev

Publish install files:

    php artisan vendor:publish --tag=asimshazad.general

General install including:

- public
- lang
- layouts
- auth
- backend
- users

Publish advanced files (1 by 1):

    php artisan vendor:publish --tag=asimshazad.config
    php artisan vendor:publish --tag=asimshazad.seo.config
    php artisan vendor:publish --tag=asimshazad.public
    php artisan vendor:publish --tag=asimshazad.lang
    php artisan vendor:publish --tag=asimshazad.layouts
    php artisan vendor:publish --tag=asimshazad.auth.view
    php artisan vendor:publish --tag=asimshazad.backend.view
    php artisan vendor:publish --tag=asimshazad.users.view

Publish all migrations files:

    php artisan vendor:publish --tag=asimshazad.migrations

Publish all stubs files:

    php artisan vendor:publish --tag=asimshazad.stubs

Publish all views files:

    php artisan vendor:publish --tag=asimshazad.all.view

Publish admin route files:

    php artisan vendor:publish --tag=asimshazad.admin.route

Add the `AdminUser` and `UserTimezone` traits to your `User` model:

    use asimshazad\simplepanel\Traits\AdminUser;
    use asimshazad\simplepanel\Traits\UserTimezone;
    
    class User extends Authenticatable
    {
        use Notifiable, AdminUser, UserTimezone;

Add this in your controller.php
    use \asimshazad\simplepanel\Traits\Controller;

    class Controller extends BaseController
    {
        use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
        use \asimshazad\simplepanel\Traits\Controller;

Run the migrations:

    php artisan migrate


### Alternative installation:

Install laravel:

    composer create-project laravel/laravel --prefer-dist appName

Create directories in terminal:

    cd appName; mkdir packages; cd packages; mkdir asimshazad; cd asimshazad; git clone https://github.com/asimshazad/simplepanel.git

Add this in your composer.json under scripts section:

    "require-dev": {
        "asimshazad/simplepanel": "*"
    },

    "repositories": {
        "asimshazad/simplepanel": {
            "type": "path",
            "url": "/path/to/your/appName/packages/asimshazad/simplepanel"
        }
    }

### Alternative installation 2:

Need packager to ease your work

    $ composer require jeroen-g/laravel-packager --dev

Import package from github

    $ php artisan packager:git git@github.com:asimshazad/simplepanel.git

Add this in your composer.json under scripts section:

    "require-dev": {
        "asimshazad/simplepanel": "*"
    },

Run composer update

### Logging In

Visit `(APP_URL)/admin` to access the admin panel.

The default admin login is:

    Email Address: admin@example.com
    Password: admin123
