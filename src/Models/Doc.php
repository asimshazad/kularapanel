<?php

namespace Khludev\KuLaraPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Khludev\KuLaraPanel\Traits\DynamicFillable;
use Khludev\KuLaraPanel\Traits\UserTimezone;
use Parsedown;

class Doc extends Model
{
    use DynamicFillable, UserTimezone, NodeTrait;

    public function markdown()
    {
        return (new Parsedown())->text($this->content);
    }
}
