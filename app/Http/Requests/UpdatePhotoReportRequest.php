<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotoReportRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'advertising_object_id' => [
                'required',
                'integer',
                'exists:advertising_objects,id',
            ],

            'photo_report_status_id' => [
                'required',
                'integer',
                'exists:photo_report_statuses,id',
            ],

            'comment' => [
                'nullable',
                'string',
            ],

            'checked_by' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'checked_at' => [
                'nullable',
                'date',
            ],

            'review_comment' => [
                'nullable',
                'string',
            ],
        ];
    }
}
