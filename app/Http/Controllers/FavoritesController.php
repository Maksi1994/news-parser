<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Http\Resources\Favorites\FavoritesCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{

    public function toggle(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'type'=> 'required|in:news',
           'type_id' => 'required|numeric',
        ]);
        $success = false;


        if (!$validator->fails()) {
            $success = Favorite::saveOne($request);
        }

        return $this->success($success);
    }

    public function getList(Request $request)
    {
        $favorites = Favorite::getList($request)
            ->with('author')
            ->all();

        return new FavoritesCollection($favorites);
    }

    public function delete(Request $request)
    {
        $success = (boolean)Favorite::destroy($request->id);

        return $this->success($success);
    }
}
