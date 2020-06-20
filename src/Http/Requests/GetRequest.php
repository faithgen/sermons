<?php

namespace FaithGen\Sermons\Http\Requests;

use FaithGen\SDK\Models\Ministry;
use FaithGen\Sermons\SermonHelper;
use FaithGen\Sermons\SermonService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
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
        if (auth()->user() instanceof Ministry) {
            return $this->user()->can('view', $sermonService->getSermon());
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('You are not allowed to transact on this sermon.');
    }
}
