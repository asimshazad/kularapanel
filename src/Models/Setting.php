<?php

namespace Khludev\KuLaraPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Khludev\KuLaraPanel\Traits\UserTimezone;

class Setting extends Model
{
    use  UserTimezone;
    protected $fillable = ['id', 'key', 'value', 'created_at', 'updated_at', 'no_edit_val', 'no_edit_key',];

    public function getValueAttribute($value)
    {
    	if (json_decode($value)) {
    		return json_decode($value,1);
    	}
    	return $value;
    }
    public function setValueAttribute($value)
    {
    	if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
}
