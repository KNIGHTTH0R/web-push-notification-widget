<?php

namespace App\Http\Requests;

use Auth;
use App\Http\Requests\Request;

class StoreNotificationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:36',
            'body' => 'required|max:80',
            'icon_url' => 'required',
            'redirect_url' => 'required'
        ];
    }
}
