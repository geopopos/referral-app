<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'required|string|max:255',
            'status' => 'nullable|in:new,contacted,qualified,appointment_scheduled,proposal_sent,closed,lost',
            'notes' => 'nullable|string',
            'referrer_id' => 'nullable|exists:users,id',
            'source' => 'nullable|string|max:255',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'pipeline_stage' => 'nullable|in:lead,qualified,appointment,proposal,negotiation,closed_won,closed_lost',
            'appointment_date' => 'nullable|date',
            'proposal_sent_date' => 'nullable|date',
            'close_date' => 'nullable|date',
            'monthly_retainer' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The lead name is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'company.required' => 'The company name is required.',
            'referrer_id.exists' => 'The selected referrer does not exist.',
            'monthly_retainer.numeric' => 'The monthly retainer must be a number.',
            'monthly_retainer.min' => 'The monthly retainer must be at least 0.',
        ];
    }
}
