<?php

namespace asimshazad\simplepanel\Models;

use Illuminate\Database\Eloquent\Model;
use asimshazad\simplepanel\Traits\UserTimezone;

class Permission extends Model
{
    use  UserTimezone;

    protected $fillable =['id', 'group', 'name'];
    // roles relationship
    public function roles()
    {
        return $this->belongsToMany(config('asimshazad.models.role'));
    }

    // users relationship
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'));
    }

    // create permission group
    public function createGroup($group, $names = [])
    {
        foreach ($names as $name) {
            $this->create([
                'group' => $group,
                'name' => $name,
            ]);
        }
    }
}
