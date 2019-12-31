<?php

namespace App\Http\Controllers;

use App\Http\Resources\News\NewsCollection;
use App\Http\Resources\News\NewsResource;
use App\Jobs\ParseHabrahabr;
use App\Jobs\Parsers\Habrahabr\RunParcing;
use App\News;
use App\NewsSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{

    public function getList(Request $request)
    {
        $news = News::with(['source'])
            ->withCount(['comments', 'likes'])
            ->getFrontendList($request)
            ->paginate(10, '*', null, $request->page ?? 1);

        return new NewsCollection($news);
    }

    public function getPopular()
    {
        $news = News::with(['source'])
            ->withCount(['comments', 'likes'])
            ->getPopularNews()
            ->get();

        return new NewsCollection($news);
    }

    public function getOne(Request $request)
    {
        $news = News::with(['source'])
            ->withCount(['comments', 'likes'])
            ->find($request->id);

        return new NewsResource($news);
    }

    public function dispatchJob(Request $request)
    {
        RunParcing::dispatchNow(NewsSource::find(1));
    }

}
