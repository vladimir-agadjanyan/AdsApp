<?php

namespace App\Http\Requests;

use App\Models\City;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateAdvertisingObjectRequest extends FormRequest
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
            'contract_id' => ['required', 'integer', 'exists:contracts,id'],
            'advertising_type_id' => ['required', 'integer', 'exists:advertising_types,id'],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'object_status_id' => ['required', 'integer', 'exists:object_statuses,id'],

            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],

            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],

            'note' => ['nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {

                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $city = City::find($this->city_id);

                if ($city && $city->region_id !== (int) $this->region_id) {
                    $validator->errors()->add(
                        'city_id',
                        'Выбранный город не принадлежит указанному региону.'
                    );
                }
            },
        ];
    }
}
