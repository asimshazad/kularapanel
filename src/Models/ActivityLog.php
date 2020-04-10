<?php

namespace Khludev\KuLaraPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Khludev\KuLaraPanel\Traits\UserTimezone;

class ActivityLog extends Model
{
    use UserTimezone;

    protected $fillable = ['id', 'user_id', 'model_id', 'model_class', 'message', 'data', 'created_at'];

    const UPDATED_AT = null;

    protected $casts = [
        'data' => 'array',
    ];

    // user relationship
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'))->withDefault(['name' => null]);
    }

    // dynamic model
    public function model()
    {
        return $this->model_class ? app($this->model_class)->find($this->model_id) : null;
    }
}
