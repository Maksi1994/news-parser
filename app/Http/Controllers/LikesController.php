<?php

namespace App\Http\Controllers;

use App\Http\Resources\Likes\LikesCollection;
use App\Like;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikesController extends Controller
{

    public function toggle(Request $request)
    {
        $model = null;
        $success = false;
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required|in:news',
            'type_id' => 'required|exists:likes_type,id',
        ]);

        if (!$validation->fails()) {
            switch ($request->type) {
                case 'news':
                    $model = News::find($request->id);
            }

            if (!empty($model)) {
                $userLikeModel = $model->likes()->where('user_id', 1)->first();

                if ($userLikeModel) {
                    $userLikeModel->delete();
                } else {
                    $model->likes()->create([
                        'user_id' => 1,
                        'type_id' => $request->type_id
                    ]);
                }

                $success = true;
            }
        }

        return $this->success($success);
    }

    public function getAll(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'type' => 'required|in:news',
            'page' => 'required|numeric'
        ]);
        $likes = [];
        $morphType = '';

        if (!$validation->fails()) {
            switch ($request->type) {
                case 'news':
                    $morphType = News::class;
            }

            $likes = Like::where([
                ['user_id', '=', 1],
                ['likeable_type', '=', $morphType]
            ])->paginate(10, '*', null, $request->page);
        }

        return new LikesCollection($likes);
    }

}
