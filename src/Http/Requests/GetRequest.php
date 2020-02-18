<?php

namespace FaithGen\Sermons\Http\Requests;

use FaithGen\SDK\Models\Ministry;
use FaithGen\Sermons\SermonHelper;
use FaithGen\Sermons\SermonService;
use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(SermonService $sermonService)
    {
        if (auth()->user() instanceof Ministry) return $this->user()->can('view', $sermonService->getSermon());
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sermon_id' => SermonHelper::$idValidation
        ];
    }
}
