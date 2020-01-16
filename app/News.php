<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class News extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    public function source()
    {
        return $this->belongsTo(NewsSource::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function category() {
        return $this->belongsTo(NewsCategory::class);
    }

    public function scopeGetBackendList($query, Request $request)
    {

        $query->when($request->orderType === 'new', function ($q) use ($request) {
            $q->orderBy('created_at', $request->order ?? 'desc');
        });

        $query->when($request->orderType === 'source', function ($q) use ($request) {
            $q->orderBy('source_id', $request->order ?? 'desc');
        });

        return $query;
    }

    public static function scopeGetPopularNews($query, Request $request)
    {
        $query->when($request->orderType === 'discussing', function ($q) use($request) {
            $q->orderBy('comments_count', $request->order);
        });

        $query->when($request->orderType === 'likeable', function ($q) use ($request) {
            $q->orderBy('like_count', $request->order);
        });


        return $query;
    }

    public function scopeGetFrontendList($query, Request $request)
    {
        $query->when($request->type === 'new', function ($q) use ($request) {
            $q->orderBy('created_at', $request->order ?? 'desc');
        });

        $query->when($request->type === 'popular', function ($q) use ($request) {
            $q->orderBy('likes_count', $request->order ?? 'desc');
        });

        return $query;
    }
}
