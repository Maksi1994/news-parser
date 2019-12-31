<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    public $timestamps = true;
    protected $guarded = [];
    protected $table = 'categories';

    public function articles() {
        return $this->hasMany(News::class, 'category_id');
    }

    public function source() {
        return $this->belongsTo(NewsSource::class, 'source_id');
    }

}
