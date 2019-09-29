<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\NewsSource;
use App\Resources\NewsSource\{NewsSourceResource, NewsSourceCollection};

class NewsSourcesController extends Controller
{
    public function save(Request $request) {
        $validation = Validator::make($request->all(), [
          'id' => 'exists:news_sources',
          'name' => 'required',
          'url' => 'required'
        ]);
        $success = false;

        if (!$validation->fails()) {
            NewsSource::updateOrCreate([
              'id'=> $request->id
            ], [
                'name' => $request->name,
                'url' => $request->url
            ]);
            $success = true;
        }

        return $this->success($success);
    }

    public function getOne(Request $request) {
        $news = NewsSource::withCount('news')->find($request->id);

        return new NewsSourceResource($news);
    }

    public function getList(Request $request) {
        $newsList = NewsSource::withCount('news')
        ->getList($request)
        ->paginate(20, null, '*', $request->page ?? 1);

        return new NewsCollection($newsList);
    }

    public function delete(Request $request) {
        $success = (boolean) NewsSource::destroy($request->id);

        return $this->success($success);
    }


}
