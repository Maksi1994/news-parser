<?php

namespace App\Jobs;

use App\News;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Date;
use Sunra\PhpSimple\HtmlDomParser;

class TestProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $todayNews = News::whereDate('created_at', Carbon::now()->toDateString())->get();
        $parsedNews = [];
        $html_text = HtmlDomParser::file_get_html('https://www.pravda.com.ua/news/');
        $newsPage = HtmlDomParser::str_get_html($html_text);

        foreach ($newsPage->find('.article') as $oneItem) {
            $newItem = [
                'title' => $oneItem->find('.title')->plaintext,
                'body' => $oneItem->find('.article__subtitle')->plaintext
            ];

            if ($todayNews->where('title', '=', $newItem['title'])->count() === 0) {
                $parsedNews[] = $newItem;
            }
        }

        News::createMany($parsedNews);
    }
}
