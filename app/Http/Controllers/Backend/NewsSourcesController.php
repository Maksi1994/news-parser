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
            'source' => 'required|url',
            'name' => 'required|min:3',
            'active_parse' => 'boolean',
            'parse_interval' => 'required|in:1,15,30',
            'show' => 'boolean',
        ]);
        $success = false;

        if (!$validation->fails()) {
            $id = NewsSource::updateOrCreate(['id' => $request->id], $request->all())->id;
            $success = true;
        }

        return response()->json(compact('id', 'success'));
    }

    public function getOne(Request $request) {
        $news = NewsSource::withCount([
          'news'
        ])->with([
          'news' => function($q) {
           $q->latest()->limit(1);
        },
          'parsed_by_categories'
        ])->find($request->id);

        return new NewsSourceResource($news);
    }

    public function getList(Request $request) {
        $newsList = NewsSource::withCount('news')
        ->orderBy('news_count', 'desc')
        ->paginate(20, '*', null, $request->page ?? 1);

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
