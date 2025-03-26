<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'text' => 'required|string',
            'media' => 'nullable|array',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:20480',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Post sarlavhasi kerak.',
            'description.required' => 'Post tavsifi kerak.',
            'text.required' => 'Post matni kerak.',
            'media.*.file' => 'Har bir yuklangan fayl to‘g‘ri formatda bo‘lishi kerak.',
            'media.*.mimes' => 'Faqat jpg, jpeg, png yoki mp4 formatdagi fayllar yuklash mumkin.',
            'media.*.max' => 'Har bir fayl hajmi 20MB dan oshmasligi kerak.',
            'category_id.required' => 'Kategoriya tanlanishi kerak.',
            'category_id.exists' => 'Tanlangan kategoriya mavjud emas.',
        ];
    }
}
