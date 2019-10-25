<?php

namespace FaithGen\Sermons\Http\Requests;

use FaithGen\Sermons\SermonHelper;
use FaithGen\Sermons\Models\Sermon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $sermon = Sermon::findOrFail(request()->sermon_id);
        return $this->user()->can('sermon.update', $sermon);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => SermonHelper::$titleValidation,
            'preacher' => SermonHelper::$titleValidation,
            'main_verses' => 'required|array',
            'date' => 'required|date',
            'reference_verses' => 'array',
            'sermon' => 'required|string|min:50',
            'resource' => 'sometimes|url',
            'sermon_id' => SermonHelper::$idValidation
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('You can`t edit a sermon that you not own');
    }
}