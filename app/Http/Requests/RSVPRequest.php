<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RSVPRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array {
        return [
            'status' => ['required','in:going,interested,uninterested'],
        ];
    }
}
