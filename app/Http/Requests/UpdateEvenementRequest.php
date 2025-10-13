<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEvenementRequest extends FormRequest
{
    public function authorize(): bool {
        $event = $this->route('event');
        return $event && $this->user()?->can('update', $event);
    }

    public function rules(): array {
        $event = $this->route('event');
        return [
            'title' => ['required','string','max:160'],
            'slug' => ['required','string','max:180','alpha_dash', Rule::unique('evenements','slug')->ignore($event?->id)],
            'summary' => ['nullable','string','max:280'],
            'description' => ['nullable','string'],
            'starts_at' => ['required','date'],
            'ends_at' => ['required','date','after:starts_at'],
            'timezone' => ['required','string','max:64'],
            'location_text' => ['nullable','string','max:255'],
            'visibility' => ['required','in:public,private'],
            'capacity' => ['nullable','integer','min:0'],
            'cover_image' => ['nullable','image','max:2048'],
        ];
    }
}
