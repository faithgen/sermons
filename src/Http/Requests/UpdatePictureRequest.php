<?php

namespace FaithGen\Sermons\Http\Requests;

use FaithGen\Sermons\SermonHelper;
use FaithGen\Sermons\Models\Sermon;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePictureRequest extends FormRequest
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
            'image' => 'base64image',
            'sermon_id' => SermonHelper::$idValidation,
            'hasImage' => 'required|boolean'
        ];
    }
}
