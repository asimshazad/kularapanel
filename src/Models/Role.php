<?php

namespace asimshazad\simplepanel\Models;

use Illuminate\Database\Eloquent\Model;
use asimshazad\simplepanel\Traits\UserTimezone;

class Role extends Model
{
    use  UserTimezone;

    protected $fillable = ['id', 'name', 'admin', 'created_at', 'updated_at'];

    // permissions relationship
    public function permissions()
    {
        return $this->belongsToMany(config('asimshazad.models.permission'));
    }
}
