<?php

namespace FaithGen\Sermons\Http\Resources\Lists;

use Carbon\Carbon;
use FaithGen\Sermons\SermonHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class Sermon extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'preacher' => [
                'name' => $this->preacher,
                'avatar' => SermonHelper::getAvatar($this->resource)
            ],
            'date' => SermonHelper::getDates(Carbon::parse($this->date)),
            'comments' => [
                'count' => number_format($this->comments()->count()),
            ]
        ];
    }
}