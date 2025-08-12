<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateGallery extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $galleryId = $this->route('gallery')->id;
        return [
            'title' => 'required|unique:galleries,name' . $galleryId . '|max:255|string',
         
            'visibility' => 'nullable|string',

            
         
            // Validasi untuk struktur files yang kompleks dengan base64
            'files' => 'required|array|min:1|max:10',
            'files.*.file' => 'required|array',
            'files.*.file.name' => 'required|string|max:255',
            'files.*.file.size' => [
                'required',
                'numeric',
                'max:10485760', // 10MB dalam bytes
            ],
            'files.*.file.type' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $allowedTypes = [
                        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
                        'video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/flv', 'video/webm'
                    ];
                    
                    if (!in_array($value, $allowedTypes)) {
                        $fail('File harus berupa gambar (jpeg, jpg, png, gif, webp) atau video (mp4, avi, mov, wmv, flv, webm).');
                    }
                },
            ],
            'files.*.id' => 'required|string',
            'files.*.preview' => 'nullable|string',
            // Tambahkan validasi untuk base64 data
            'files.*.base64Data' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Validasi format base64
                    if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $value)) {
                        $fail('Invalid base64 data format.');
                    }
                    
                    // Cek apakah bisa di-decode
                    $decoded = base64_decode($value, true);
                    if ($decoded === false) {
                        $fail('Invalid base64 data.');
                    }
                },
            ],
        ];
    }
}
