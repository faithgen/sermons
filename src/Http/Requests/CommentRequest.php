<?php

namespace FaithGen\Sermons\Http\Requests;

use FaithGen\SDK\Models\Ministry;
use FaithGen\Sermons\SermonHelper;
use FaithGen\Sermons\SermonService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(SermonService $sermonService)
    {
        if (auth()->user() instanceof Ministry) return $this->user()->can('sermon.view', $sermonService->getSermon());
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
            'sermon_id' => SermonHelper::$idValidation,
            'comment' => 'required'
        ];
    }

    function failedAuthorization()
    {
        throw new AuthorizationException('You do not have access to this sermon');
    }
}
