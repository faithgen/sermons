<?php

namespace FaithGen\Sermons\Http\Requests;

use FaithGen\Sermons\SermonHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('sermon.create');
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
            'image' => 'base64image',
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('You can`t create more than 2 sermons when in free subscription mode, consider upgrading');
    }
}
