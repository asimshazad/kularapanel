<?php

namespace Khludev\KuLaraPanel\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Khludev\KuLaraPanel\Traits\DynamicFillable;
use Khludev\KuLaraPanel\Traits\UserTimezone;

class Seotool extends Eloquent
{
    use DynamicFillable, UserTimezone;
    protected $dates = [];



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
