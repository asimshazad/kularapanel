<?php

namespace Khludev\KuLaraPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Khludev\KuLaraPanel\Traits\UserTimezone;
use Parsedown;

class Doc extends Model
{
    use UserTimezone, NodeTrait;

    protected $fillable = ['id', 'type', 'title', 'slug', 'content', 'system', '_lft', '_rgt', 'parent_id', 'created_at', 'updated_at'];

    public function markdown()
    {
        return (new Parsedown())->text($this->content);
    }
}
