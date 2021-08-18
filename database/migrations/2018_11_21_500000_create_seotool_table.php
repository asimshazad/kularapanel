<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeotoolTable extends Migration
{
    public function up()
    {
        // create table
        Schema::create('seotools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model')->nullable();
            $table->integer('model_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('canonical')->nullable();
            $table->json('metas')->nullable();
            $table->json('keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_url')->nullable();
            $table->text('og_description')->nullable();
            $table->json('og_properties')->nullable();
            $table->json('og_images')->nullable();
            $table->json('og_model')->nullable();
            $table->string('jsonld_title')->nullable();
            $table->string('jsonld_type')->nullable();
            $table->string('jsonld_url')->nullable();
            $table->text('jsonld_description')->nullable();
            $table->json('jsonld_images')->nullable();
            $table->string('twitter_title')->nullable();
            $table->string('twitter_site')->nullable();
            $table->string('created_by')->default(1)->nullable();
            $table->string('updated_by')->default(1)->nullable();
            $table->timestamps();
        });

        // add permissions
        app(config('asimshazad.models.permission'))->createGroup('Seotools', ['Create Seotools', 'Read Seotools', 'Update Seotools', 'Delete Seotools']);
    }

    public function down()
    {
        // drop table
        Schema::dropIfExists('seotools');
        // delete permissions
        app(config('asimshazad.models.permission'))->where('group', 'Seotools')->delete();
    }
}
