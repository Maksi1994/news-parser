<?php

namespace App\Http\Resources\Likes;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LikesTypeCollection extends ResourceCollection
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
