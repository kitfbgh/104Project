<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'price' => 'required|int|max:10000000|min:0',
            'quantity' => 'required|int|min:1|max:10000',
            'image' => 'image|mimes:jpeg,jpg,png,gif,svg|max:10000',
            'category' => 'required|string|max:10',
            'origin_price' => 'required|int|max:10000000|min:0',
            'unit' => 'required|string|max:5',
            'imageUrl' => 'string|url'
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
            //attribute會帶入上方rules裡面有用到require規則的屬性(id, name)
            'required' => ':attribute 不能為空',
            'max' => ':attribute 超出最大值',
            'min' => ':attribute 太低囉',
            'image' => '檔案請選擇照片格式',
            'url' => '請輸入正確的網址'
        ];

        return $messages;
    }
}
