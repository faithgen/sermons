<?php

namespace FaithGen\Sermons\Http\Resources;

use Carbon\Carbon;
use FaithGen\SDK\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use InnoFlash\LaraStart\Helper;

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
            'preacher' => [
                'name' => $this->preacher,
                'avatar' => ImageHelper::getImage('sermons', $this->image, config('faithgen-sdk.ministries-server')),
            ],
            'date' => Helper::getDates(Carbon::parse($this->date)),
            'comments' => [
                'count' => number_format($this->comments()->count()),
            ]
        ];
    }
}
