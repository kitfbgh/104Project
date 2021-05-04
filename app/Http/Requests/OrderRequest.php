<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'email' => 'required|string|email|max:50',
            'name' => 'required|string|max:30',
            'tel' => 'required|string|min:10|regex:/09[0-9]{8}/',
            'address' => 'required|string|max:60',
            'payment' => 'required|string|max:10|in:"貨到付款"',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'required' => ':attribute 不能為空',
            'max' => ':attribute 超出最大值',
            'min' => ':attribute 太短囉',
            'email' => '請輸入正確的email格式',
            'regex' => ':attribute 格式錯誤',
            'in' => ':attritube 值被修改，你很壞喔！'
        ];

        return $messages;
    }
}
