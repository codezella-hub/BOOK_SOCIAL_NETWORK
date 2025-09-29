<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $this->book->id,
            'synopsis' => 'nullable|string',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'archived' => 'boolean',
            'shareable' => 'boolean',
            'category_id' => 'required|exists:categories,id'
        ];
    }
}
