<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Store_Book_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:40|regex:/^[\p{L}\s]+$/u|unique:books,title', 
            'author'  => 'required|string|max:40|regex:/^[\p{L}\s]+$/u',
            'published_at'  => 'required|date',
            'category_id' => 'required|integer|exists:categories,id',
        ];
    }
    //===========================================================================================================================
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'status' => 'error 422',
            'message' => 'فشل التحقق يرجى التأكد من المدخلات',
            'errors' => $validator->errors(),
        ]));
    }
    //===========================================================================================================================
    protected function passedValidation()
    {
        //تسجيل وقت إضافي
        Log::info('تمت عملية التحقق بنجاح في ' . now());

    }
    //===========================================================================================================================
    public function attributes(): array
    {
        return [
            'title' => 'عنوان الكتاب', 
            'author' => 'اسم الكاتب', 
            'published_at' => 'تاريخ النشر', 
            'is_active' => 'حالة الكتاب',
            'category_id' => 'اسم التصنيف',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'required' => 'حقل :attribute مطلوب',
            'string' => 'يجب أن يكون الحقل :attribute سلسلة محارف',
            'regex' => 'يجب أن يحوي  :attribute على أحرف فقط',
            'max' => 'الحد الأكبر لطول :attribute هو 40',
            'unique' => 'إن حقل :attribute موجود مسبقاً , يجب أن يكون حقل :attribute غير مكرر',
            'date' => 'يجب أن يكون الحقل :attribute تاريخاً',
            'integer' => 'يجب أن يكون حقل :attribute من تمط integer',
            'exists' => 'يجب أن يكون حقل :attribute موجود سابقاً ضمن التصنيفات المخزنة',
        ];
    }
}
