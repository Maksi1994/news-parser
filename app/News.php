<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class News extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    public function source() {
      return $this->belongsTo(NewsSource::class);
    }

    public function scopeGetList($query, Request $request) {

        $query->when($request->orderType === 'new', function($q) use($request) {
            $q->orderBy('created_at', $request->order ?? 'desc');
        });

        $query->when($request->orderType === 'source', function($q) use($request) {
            $q->orderBy('source_id', $request->order ?? 'desc');
        });

        return $query;
    }
}
