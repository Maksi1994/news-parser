<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Favorite extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    public function favorable()
    {
        return $this->morphTo();
    }

    public function author()
    {
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

            if ($model->favorites()->exists()) {
                $model->favorites()->where('author_id', 1)->delete();
            } else if ($model->favorites()->count() <= 5) {
                $model->favorites()->create(['author_id' => 1]);
            }
        }

        return $success;
    }

    public static function getList(Request $request)
    {
        switch ($request->type) {
            case 'news':
                $model = News::where('id', $request->type_id)->first();
        }


        return $model->favorites()
            ->where('author_id', $request->author_id)
            ->first();
    }
}
