<?php

namespace Khludev\KuLaraPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Khludev\KuLaraPanel\Traits\UserTimezone;

class Role extends Model
{
    use  UserTimezone;

    protected $fillable = ['id', 'name', 'admin', 'created_at', 'updated_at'];

    // permissions relationship
    public function permissions()
    {
        return $this->belongsToMany(config('kulara.models.permission'));
    }
}
