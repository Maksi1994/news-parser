<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\Likes\LikesTypeCollection;
use App\LikeType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LikesController extends Controller
{

    public function saveType(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:likes_type',
            'name' => 'required|unique:likes_type',
        ]);
        $success = false;


        if (!$validation->fails()) {
            LikeType::updateOrCreate([
                'id' => $request->id
            ], [
                'name' => $request->name
            ]);

            $success = true;
        }

        return $this->success($success);
    }

    public function getAllTypes(Request $request)
    {
        $types = LikeType::withCount('likes')->get();

        return new LikesTypeCollection($types);
    }

    public function delete(Request $request)
    {
        $success = (boolean)LikeType::destroy($request->id);

        return $this->success($success);
    }
}
