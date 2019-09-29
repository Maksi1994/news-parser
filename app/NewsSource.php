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
      return $this->hasMany(News::class);
    }

    public function scopeGetList($query, Request $request) {

      $query->when($request->orderType == 'new', function($q) use ($request) {
          $q->orderBy('created_at', $request->order ?? 'desc');
      });

      $query->when($request->orderType == 'count', function($q) use ($request) {
          $q->orderBy('news_count', $request->order ?? 'desc');
      });

      return $query;
    }
}
