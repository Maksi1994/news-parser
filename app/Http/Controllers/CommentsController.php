<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Resources\Comments\CommentsCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{

    public function toggle(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:comments',
            'type' => 'required|in:news',
            'type_id' => 'required',
            'body' => 'required|min:3'
        ]);
        $success = false;

        if (!$validation->fails()) {
            $success = Comment::toggle($request);
        }

        return $this->success($success);
    }

    public function getList(Request $request)
    {
        $comments = Comment::getList($request)
            ->with('author')
            ->paginate(10, '*', null, $request->page ?? 1);

        return new CommentsCollection($comments);
    }

    public function deleteOne(Request $request)
    {
        $success = (boolean)Comment::destroy($request->id);

        return $this->success($success);
    }
}
