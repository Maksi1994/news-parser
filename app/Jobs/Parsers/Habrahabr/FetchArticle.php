<?php

namespace App\Jobs\Parsers\Habrahabr;

use App\Classes\Parser;
use App\News;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchArticle extends Parser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $articleInfo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($articleInfo)
    {
        $this->articleInfo = $articleInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $newsPage = $this->fetchPage($this->articleInfo['link']);

        News::create([
            'title' => trim($newsPage->find('.post__title-text', 0)->plaintext),
            'body' => $newsPage->find('.post__text', 0)->innertext,
            'category_id' => $this->articleInfo['category_id'],
            'source_id' => $this->articleInfo['source_id']
        ]);
    }
}
