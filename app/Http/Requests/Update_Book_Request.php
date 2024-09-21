<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Update_Book_Request extends FormRequest
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
            'title' => 'sometimes|nullable|string|max:40|regex:/^[\p{L}\s]+$/u|unique:books,title', 
            'author'  => 'sometimes|nullable|string|max:40|regex:/^[\p{L}\s]+$/u',
            'published_at'  => 'sometimes|nullable|date',
            'is_active'  => 'sometimes|nullable|boolean',
            'category_id' => 'sometimes|nullable|integer|exists:categories,id',
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
            'string' => 'يجب أن يكون الحقل :attribute سلسلة محارف',
            'regex' => 'يجب أن يحوي  :attribute على أحرف فقط',
            'max' => 'الحد الأكبر لطول :attribute هو 40',
            'unique' => 'إن حقل :attribute موجود مسبقاً , يجب أن يكون حقل :attribute غير مكرر',
            'date' => 'يجب أن يكون الحقل :attribute تاريخاً',
            'boolean' => 'يجب أن تكون :attribute إما true أو false.',
            'integer' => 'يجب أن يكون حقل :attribute من تمط integer',
            'exists' => 'يجب أن يكون حقل :attribute موجود سابقاً ضمن التصنيفات المخزنة',
        ];
    }
}
