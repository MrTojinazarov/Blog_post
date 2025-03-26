<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Post sarlavhasi kerak.',
            'title.string' => 'Post sarlavhasi matn bo\'lishi kerak.',
            'title.max' => 'Post sarlavhasi 255 ta belgidan oshmasligi kerak.',
            'description.required' => 'Post tavsifi kerak.',
            'description.string' => 'Post tavsifi matn bo\'lishi kerak.',
            'text.required' => 'Post matni kerak.',
            'text.string' => 'Post matni matn bo\'lishi kerak.',
            'category_id.required' => 'Kategoriya tanlanishi kerak.',
            'category_id.exists' => 'Tanlangan kategoriya mavjud emas.',
            'media.array' => 'Rasm yoki videolarni to\'g\'ri yuboring.',
            'media.*.file' => 'Har bir fayl to\'g\'ri formatda bo\'lishi kerak.',
            'media.*.mimes' => 'Faqat rasm (jpg, jpeg, png) yoki video (mp4, mov, avi) formatlari qo\'llab-quvvatlanadi.',
            'media.*.max' => 'Fayl hajmi 20MB dan oshmasligi kerak.',
        ];
    }
}
