<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvenementRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:evenements,slug',
            'summary' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'timezone' => 'required|string|max:64',
            'location_text' => 'nullable|string|max:255',
// In StoreEvenementRequest
'status' => 'nullable|in:draft,published,cancelled',
            'visibility' => 'required|in:public,private',
            'capacity' => 'nullable|integer|min:1',
            'cover_image' => 'nullable|image|max:5120',

            // New: coordinates
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
        ];
    }
}
