<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LikeType extends Model
{
    protected $table = 'likes_type';
    public $timestamps = true;
    public $guarded = [];

    public function likes() {
        return $this->hasMany(Like::class, 'type_id');
    }
}
