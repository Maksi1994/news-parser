<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

    public $timestamps = true;
    public $guarded = [];

    public function likeable() {
        return $this->morphTo();
    }

    public function type() {
        return $this->belongsTo(LikeType::class);
    }
}
