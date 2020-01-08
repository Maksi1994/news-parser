<?php

namespace App\Http\Resources\NewsSources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NewsSourcesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
