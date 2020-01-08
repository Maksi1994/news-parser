<?php

namespace App\Http\Resources\Backend\NewsSources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsSourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'news_count' => $this->news_count,
          'news' => $this->news,
          'parsed_by_categories' => $this->parsed_by_categories,
          'source' => $this->source,
          'logo' => asset('images/source-default-icon.png'),
          'show' => $this->show,
          'active_parse' => $this->active_parse,
          'parse_interval' => $this->parse_interval,
          'implemented' => $this->implemented
        ];
    }
}
