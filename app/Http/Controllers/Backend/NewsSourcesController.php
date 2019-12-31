<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\Backend\NewsSources\NewsSourceCollection;
use App\Http\Resources\Backend\NewsSources\NewsSourceResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\NewsSource;

class NewsSourcesController extends Controller
{
    public function save(Request $request) {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:news_sources',
            'url' => 'required|url',
            'active_parse' => 'required|boolean',
            'parse_interval' => 'required|in:1,10,15,30,60',
            'show' => 'required|boolean',
        ]);
        $success = false;

        if (!$validation->fails()) {
            NewsSource::updateOrCreate(['id' => $request->id], $request->all());
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

        return new NewsSourceCollection($newsList);
    }

    public function isUniqueName(Request $request) {
         $exists = NewsSource::where('name', $request->name)->exists();

         return $this->success(!$exists);
    }

    public function isUniqueDomain(Request $request) {
        $exists = NewsSource::where('url', $request->url)->exists();

        return $this->success(!$exists);
    }

    public function delete(Request $request) {
        $success = (boolean) NewsSource::destroy($request->id);

        return $this->success($success);
    }


}
