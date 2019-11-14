<?php

namespace FaithGen\Sermons\Http\Requests;

use FaithGen\Sermons\SermonHelper;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
           'limit' => 'integer:min:1',
            'filter_text' => 'nullable',
            'full_sermons' => 'nullable'
        ];
    }
}
