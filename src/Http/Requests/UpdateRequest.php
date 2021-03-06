<?php

namespace FaithGen\Sermons\Http\Requests;

use FaithGen\Sermons\SermonHelper;
use FaithGen\Sermons\SermonService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title'            => SermonHelper::$titleValidation,
            'preacher'         => SermonHelper::$titleValidation,
            'main_verses'      => 'required|array',
            'date'             => 'required|date',
            'reference_verses' => 'array',
            'sermon'           => 'required|string|min:50',
            'resource'         => 'sometimes|url',
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('You can`t edit a sermon that you do not own');
    }
}
