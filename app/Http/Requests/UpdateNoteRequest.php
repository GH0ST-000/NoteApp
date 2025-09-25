<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'group_id' => 'nullable|exists:groups,id',
            'is_pinned' => 'boolean',
            'is_published' => 'boolean',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ];
    }

    /**
     * Convert validated data to a NoteDTO.
     */
    public function toDTO(\App\Models\Note $note): \App\DTOs\NoteDTO
    {
        return new \App\DTOs\NoteDTO(
            $this->input('title'),
            $this->input('content'),
            $this->input('group_id'),
            $this->boolean('is_pinned', false),
            $this->boolean('is_published', false),
            $note->slug, // Keep the existing slug if any
            $note->image_path, // Keep the existing image path if any
            $this->file('image')
        );
    }
}
