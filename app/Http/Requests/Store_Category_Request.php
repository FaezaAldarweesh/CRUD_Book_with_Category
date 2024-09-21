<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Store_Category_Request extends FormRequest
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
            'name' => 'required|string|max:40|regex:/^[\p{L}\s]+$/u|unique:categories,name', 
            'description'  => 'nullable|string|max:250',
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
            'name' => 'اسم التصنيف', 
            'description' => 'وصف التصنيف', 
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'required' => 'حقل :attribute مطلوب',
            'string' => 'يجب أن يكون الحقل :attribute سلسلة محارف',
            'regex' => 'يجب أن يحوي  :attribute على أحرف فقط',
            'name.max' => 'الحد الأكبر لطول :attribute هو 40',
            'description.max' => 'الحد الأكبر لطول :attribute هو 250',
            'unique' => 'إن حقل :attribute موجود مسبقاً , يجب أن يكون حقل :attribute غير مكرر',
        ];
    }
}
