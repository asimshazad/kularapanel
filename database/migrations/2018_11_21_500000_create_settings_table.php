<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        // create table
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // create example setting
        app(config('kulara.models.setting'))->create([
            'key' => 'string',
            'value' => 'Hello World',
        ]);

        app(config('kulara.models.setting'))->create([
            'key' => 'array',
            'value' => ['Hi','Hello','Aloha'],
        ]);

        // add permissions
        app(config('kulara.models.permission'))->createGroup('Settings', ['Update Settings']);
    }

    public function down()
    {
        // drop table
        Schema::dropIfExists('settings');

        // delete permissions
        app(config('kulara.models.permission'))->where('group', 'Settings')->delete();
    }
}
