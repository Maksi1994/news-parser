<?php

namespace App\Jobs\Parsers\Habrahabr;

use App\Classes\Parser;
use App\NewsCategory;
use App\NewsSource;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RunParcing extends Parser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $newsSource;

    public function __construct(NewsSource $newsSource)
    {
        $this->newsSource = $newsSource;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $categories = $this->getCategories();
        $articles = [];


        foreach ($categories as $category) {
            $categoryModel = NewsCategory::firstOrCreate(
                ['name' => $category['name']],
                [
                    'name' => $category['name'],
                    'source_id' => $this->newsSource->id
                ]
            );

            $articles = array_merge($articles, collect($this->parseList($category['link']))->map(function ($item) use ($categoryModel) {
                return [
                    'link' => $item,
                    'category_id' => $categoryModel->id,
                    'source_id' => $this->newsSource->id
                ];
            })->values()->all());
        }

        foreach ($articles as $index => $article) {
            FetchArticle::dispatch($article)->delay(now()->addSeconds($index * 2));
        }

    }

    private function getCategories()
    {
        $body = $this->fetchPage($this->newsSource->source);

        return collect($body->find('.nav-links .nav-links__item  .nav-links__item-link'))
            ->map(function ($item) {
                return [
                    'name' => $item->plaintext,
                    'link' => $item->href
                ];
            })->values()->all();
    }

    public function parseList($categoryLink)
    {
        $articlesLinks = [];

        for ($page = 1; $page < 2; $page++) {
            $body = $this->fetchPage($categoryLink . '/page' . $page);
            $links = collect($body->find('.content-list__item .post__title_link'))
                ->map(function ($item) {
                    return $item->href;
                })->values()->all();

            $articlesLinks = array_merge($articlesLinks, $links);
        }

        return $articlesLinks;
    }
}
