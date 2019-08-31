<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Backend\News\{NewsResource, NewsCollection};
use App\News;

class NewsController extends Controller
{

    public function save(Request $request) {
        $validation = Validator::make($request->all(), [
          'id' => 'exists:news',
          'title' => 'required',
          'body' => 'required',
        ]);
        $sucess = false;

        if (!$validation->fails()) {
            News::updateOrCreate([
              'id' => $request->id
            ], [
              'title' => $request->title,
              'body' => $request->body
            ]);
            $sucess = true;
        }

        return $this->success($sucess);
    }

    public function getOne(Request $request) {
        $news = News::with('source')->find($request->id);

        return new NewsResource($news);
    }

    public function getList(Request $request) {
        $news = News::with('source')
        ->getList($request)
        ->paginate(20, '*', null, $request->page ?? 1);

        return new NewsCollection($news);
    }

    public function delete(Request $request) {
        $success = (boolean) News::destroy($request->id);

        return $this->success($success);
    }
}
