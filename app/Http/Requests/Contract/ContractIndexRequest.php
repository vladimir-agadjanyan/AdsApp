<?php

namespace App\Http\Requests\Contract;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContractIndexRequest extends FormRequest
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
            'search' => ['nullable', 'string'],

            'counterparty_id' => [
                'nullable',
                'integer',
                'exists:counterparties,id',
            ],

            'sort' => [
                'nullable',
                Rule::in([
                    'number',
                    '-number',
                    'contract_date',
                    '-contract_date',
                    'start_date',
                    '-start_date',
                    'end_date',
                    '-end_date',
                ]),
            ],
            'status' => [
                'nullable',
                Rule::in([
                    'active',
                    'expiring',
                    'expired',
                ]),
            ],

            'contract_date_from' => ['nullable', 'date'],
            'contract_date_to' => ['nullable', 'date'],

            'end_date_from' => ['nullable', 'date'],
            'end_date_to' => ['nullable', 'date'],
        ];
    }
}
