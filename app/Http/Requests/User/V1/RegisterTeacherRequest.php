<?php

namespace App\Http\Requests\User\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTeacherRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name'  => 'required',
            'user_name'  => 'required|unique:users,user_name',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'required|digits:10|regex:/(0)[0-9]{9}/|unique:users,phone',
            'sex'        => 'required|boolean',
            'birthday'   => 'required|date_format:Y-m-d',
            'password'   => 'required',
        ];
    }
}
