<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMerch extends FormRequest
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
        return [
            'name' => 'required|unique:merchandises,name|max:255|string',
           'image' => 'required|image|mimes:jpg,png,jpeg|max:2048|dimensions:min_width=100,min_height=100',
           'price' => 'required|numeric|min:0|max:9999999999999.99',
            'deskripsi' => 'nullable|string',
     
            'status' => 'nullable|string',
            'visibility' => 'nullable|string',

            
            'quantity' => 'nullable|integer|min:1',
            
            // Validasi untuk struktur files yang kompleks dengan base64
            // Validasi untuk struktur files yang kompleks dengan base64
            'files' => 'nullable|array|min:1|max:10',
            'files.*.file' => 'nullable|array',
            'files.*.file.name' => 'nullable|string|max:255',
            'files.*.file.size' => [
                'nullable',
                'numeric',
                'max:10485760', // 10MB dalam bytes
            ],
            'files.*.file.type' => [
                'nullable',
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
            'files.*.id' => 'nullable|string',
            'files.*.preview' => 'nullable|string',
            // Tambahkan validasi untuk base64 data
            'files.*.base64Data' => [
                'nullable',
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
