<?php

namespace App\Http\Controllers;

use App\Http\Resources\News\NewsCollection;
use App\Http\Resources\News\NewsResource;
use App\Jobs\ParseHabrahabr;
use App\Jobs\Parsers\Habrahabr\RunParcing;
use App\{News, NewsCategory, NewsSource};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Categories\CategoriesCollection;
use App\Http\Resources\NewsSources\NewsSourcesCollection;

class NewsController extends Controller
{

    public function getList(Request $request)
    {
        $news = News::with(['source', 'category'])
            ->whereHas('category', function ($q) use ($request) {
                $q->where('id', $request->categoryId);
            })
            ->withCount(['comments', 'likes'])
            ->where('title', 'LIKE', '%' . $request->search_text . '%')
            ->getFrontendList($request)
            ->paginate(10, '*', null, $request->page ?? 1);

        return new NewsCollection($news);
    }

    public function getFilters(Request $request)
    {
        $sources = NewsSource::whereHas('news')->get();
        $categories = [];

        if (!empty($sources)) {
            $sourceId = $request->id ?? $sources->pluck('id')->first();
            $categories = NewsCategory::where('source_id', $sourceId)
                ->withCount('articles')
                ->get();
        }

        return response()->json([
            'sources' => new NewsSourcesCollection($sources),
            'categories' => new CategoriesCollection($categories)
        ]);
    }

    public function getLatestNews(Request $request)
    {
        $news = News::with(['category', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

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
