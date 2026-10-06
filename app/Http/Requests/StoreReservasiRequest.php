<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Diberikan otorisasi untuk semua user yang terotentikasi di middleware rute
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'lapangan_id' => ['required', 'exists:lapangans,id'],
            'tanggal_reservasi' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'metode_pembayaran' => ['required', 'in:cash,qris'],
            'bukti_qris' => [
                'required_if:metode_pembayaran,qris',
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048', // Maksimal ukuran file 2MB (2048 KB)
            ],
            'catatan' => ['nullable', 'string', 'max:500'],
        ];

        // Jika user adalah admin, wajib memilih pelanggan dari dropdown
        if ($this->user() && $this->user()->isAdmin()) {
            $rules['pelanggan_id'] = ['required', 'exists:pelanggans,id'];
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'lapangan_id.required' => 'Pilih lapangan olahraga.',
            'tanggal_reservasi.required' => 'Tanggal reservasi wajib diisi.',
            'tanggal_reservasi.after_or_equal' => 'Tanggal reservasi tidak boleh di masa lalu.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'pelanggan_id.required' => 'Pilih pelanggan pemesan.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran (Cash atau QRIS).',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
            'bukti_qris.required_if' => 'Bukti pembayaran QRIS wajib diunggah apabila memilih metode pembayaran QRIS.',
            'bukti_qris.file' => 'Bukti pembayaran harus berupa file yang valid.',
            'bukti_qris.mimes' => 'Format bukti pembayaran wajib berupa file JPG, JPEG, PNG, atau PDF.',
            'bukti_qris.max' => 'Ukuran file bukti pembayaran maksimal adalah 2MB.',
        ];
    }
}
