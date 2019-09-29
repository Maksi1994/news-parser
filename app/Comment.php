<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Comment extends Model
{

    public $timestamps = true;
    protected $guarded = [];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function author() {
        return $this->belongsTo(User::class);
    }

    public static function saveOne(Request $request)
    {
        $model = null;
        $success = false;

        switch ($request->type) {
            case 'news':
                $model = News::find($request->type_id);
        }

        if (!empty($model)) {
            $success = true;
            $model->comments()->updateOrCreate([
                'id' => $request->type_id
            ], [
                'body' => $request->body,
                'author_id' => 1
            ]);
        }

        return $success;
    }

    public static function getList(Request $request)
    {
        $model = null;

        switch ($request->type) {
            case 'news':
                $model = News::find($request->id);
        }

        return $model->comments();
    }


}
