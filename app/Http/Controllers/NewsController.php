<?php

namespace App\Http\Controllers;

use App\Http\Resources\News\NewsCollection;
use App\Http\Resources\News\NewsResource;
use App\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function getList(Request $request)
    {
        $news = News::with(['comments', 'source'])->getFrontendList($request);

        return new NewsCollection($news);
    }

    public function getOne(Request $request)
    {
        $news = News::with(['comments', 'source'])->find($request->id);

        return new NewsResource($news);
    }

}
