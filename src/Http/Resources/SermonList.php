<?php

namespace FaithGen\Sermons\Http\Resources;

use Carbon\Carbon;
use FaithGen\Sermons\SermonHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use InnoFlash\LaraStart\Http\Helper;

class SermonList extends JsonResource
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
            'comments' => $this->comments()->count(),
            'preacher' => [
                'name' => $this->preacher,
                'avatar' => SermonHelper::getAvatar($this->resource)
            ],
            'date' => Helper::getDates(Carbon::parse($this->date)),
            'comments' => [
                'count' => number_format($this->comments()->count()),
            ]
        ];
    }
}
