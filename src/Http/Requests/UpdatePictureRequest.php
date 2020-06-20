<?php

namespace FaithGen\Sermons\Http\Requests;

use FaithGen\Sermons\SermonService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePictureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param \FaithGen\Sermons\SermonService $sermonService
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
            'image'    => 'base64image',
            'hasImage' => 'required|boolean',
        ];
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('You are not permitted to update this sermon.');
    }
}
