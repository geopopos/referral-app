<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'lead_id' => 'nullable|exists:leads,id',
            'amount' => 'sometimes|required|numeric|min:0',
            'type' => 'sometimes|required|in:referral,fast_close_bonus,monthly_recurring',
            'status' => 'nullable|in:pending,approved,paid,cancelled',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'The user is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'lead_id.exists' => 'The selected lead does not exist.',
            'amount.required' => 'The commission amount is required.',
            'amount.numeric' => 'The commission amount must be a number.',
            'amount.min' => 'The commission amount must be at least 0.',
            'type.required' => 'The commission type is required.',
            'type.in' => 'The commission type must be one of: referral, fast_close_bonus, monthly_recurring.',
            'status.in' => 'The status must be one of: pending, approved, paid, cancelled.',
        ];
    }
}
