<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class NewsSource extends Model
{
    public $table = 'news_sources';
    public $timestamps = true;
    protected $guarded = [];

    public function news() {
      return $this->hasMany(News::class, 'source_id');
    }

    public function parsed_by_categories() {
      return $this->hasMany(NewsCategory::class, 'source_id')->withCount('articles');
    }

}
