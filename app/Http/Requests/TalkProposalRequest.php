<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TalkProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'title' => ['required', 'string','max:255'],
            'description' => ['required', 'string'],
            'speaker_id' => ['required', 'exists:speakers,id'],
            'presentation_path' => ['nullable', 'string', 'max:255'],
            'scheduled_at' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:proposal,accepted,rejected,scheduled,completed'],
         ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'A title is required.',
            'description.required' => 'A description is required.',
            'speaker_id.required' => 'A speaker is required.',
            'scheduled_at.required' => 'A scheduled date is required.',
            'status.required' => 'A status is required.',
        ];
    }
}
