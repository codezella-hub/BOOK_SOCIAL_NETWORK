<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvenementRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array {
        return [
            'title' => ['required','string','max:160'],
            'slug' => ['required','string','max:180','alpha_dash','unique:evenements,slug'],
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
