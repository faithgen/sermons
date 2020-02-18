<?php

namespace FaithGen\Sermons\Http\Requests;

use FaithGen\Sermons\SermonHelper;
use FaithGen\Sermons\SermonService;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePictureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(SermonService $sermonService)
    {
        return $sermonService->getSermon() 
            && $this->user()->can('update', $sermonService->getSermon());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'base64image',
            'sermon_id' => SermonHelper::$idValidation,
            'hasImage' => 'required|boolean'
        ];
    }
}
