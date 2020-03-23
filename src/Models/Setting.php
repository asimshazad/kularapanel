<?php

namespace Khludev\KuLaraPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Khludev\KuLaraPanel\Traits\DynamicFillable;
use Khludev\KuLaraPanel\Traits\UserTimezone;

class Setting extends Model
{
    use DynamicFillable, UserTimezone;

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
