<?php

namespace FaithGen\Sermons\Http\Resources;

use Carbon\Carbon;
use FaithGen\SDK\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use InnoFlash\LaraStart\Helper;

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
            'comments' => $this->comments()->count(),
            'comments' => [
                'count' => number_format($this->comments()->count()),
            ],
            'preacher' => [
                'name' => $this->preacher,
                'avatar' => ImageHelper::getImage('sermons', $this->image, config('faithgen-sdk.ministries-server')),
            ],
            'date' => Helper::getDates(Carbon::parse($this->date)),
            'resource' => $this['resource'],
            'verses' => [
                'main' => $this->main_verses,
                'reference' => $this->reference_verses,
            ],
            'sermon' => $this->sermon
        ];
    }
}
