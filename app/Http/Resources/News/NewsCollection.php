<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NewsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($row) {
            $clearContentText = strip_tags($row['body']);

            return [
                'id' => $row['id'],
                'title' => $row['title'],
                'short_content' => mb_substr($clearContentText, 0, 300, 'utf-8'),
                'cover_img' => $row['cover_img'] ?? asset('images/news-default-img.svg'),
                'created_at' => $row['created_at']
            ];
        });
    }
}
