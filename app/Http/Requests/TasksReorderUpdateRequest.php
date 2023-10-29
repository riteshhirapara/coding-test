<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class  TasksReorderUpdateRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'phases' => ['nullable', 'array'],
            'phases.*.id' => ['integer', 'required', 'exists:\App\Models\Phase,id'],
            'phases.*.tasks' => ['nullable', 'array'],
            'phases.*.tasks.*.id' => ['nullable', 'integer', 'required_with:phases.*.tasks', 'exists:\App\Models\Task,id'],
            'phases.*.tasks.*.position' => ['nullable', 'required_with:phases.*.tasks', 'numeric'],
        ];
    }
}
