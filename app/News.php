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

    public function likes() {
        return $this->morphMany(Like::class, 'likeable');
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

    public function scopeGetFrontendList($query, Request $request)
    {

        $query->when($request->type === 'list', function ($q) use ($request) {
            $q->paginate(10, '*', null, $request->page ?? 1);
        });

        $query->when($request->type === 'popular', function ($q) use ($request) {
            $q->orderBy('created_at', $request->order)->limit(10);
        });

        $query->when($request->type === 'latest', function ($q) use ($request) {
            $q->orderBy('created_at', $request->order)->limit(10);
        });

        return $request;
    }
}
