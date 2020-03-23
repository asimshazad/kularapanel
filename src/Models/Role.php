<?php

namespace Khludev\KuLaraPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Khludev\KuLaraPanel\Traits\DynamicFillable;
use Khludev\KuLaraPanel\Traits\UserTimezone;

class Role extends Model
{
    use DynamicFillable, UserTimezone;

    // permissions relationship
    public function permissions()
    {
        return $this->belongsToMany(config('kulara.models.permission'));
    }
}
