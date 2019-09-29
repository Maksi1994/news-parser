<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps  = true;
    protected $guarded  = [];

    public function users() {
        return $this->hasMany(User::class);
    }
}
