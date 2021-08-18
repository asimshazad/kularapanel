<?php

namespace asimshazad\simplepanel\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use asimshazad\simplepanel\Traits\UserTimezone;

class Seotool extends Eloquent
{
    use  UserTimezone;
    protected $dates = [];
    protected $fillable = [
        'id', 'model', 'model_id', 'title', 'description', 'canonical', 'metas', 'keywords',
        'og_title', 'og_url', 'og_description', 'og_properties', 'og_images', 'og_model',
        'jsonld_title', 'jsonld_type', 'jsonld_url', 'jsonld_description', 'jsonld_images',
        'twitter_title', 'twitter_site', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];


    protected $casts = ['metas' => 'array', 'keywords' => 'array', 'og_properties' => 'array', 'og_images' => 'array', 'og_model' => 'array', 'jsonld_images' => 'array'];

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function modifier()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }




}
