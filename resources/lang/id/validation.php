<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi Bahasa Indonesia
    |--------------------------------------------------------------------------
    */

    'required' => ':attribute wajib diisi.',
    'email'    => ':attribute harus berupa alamat email yang valid.',
    'min'      => ':attribute minimal harus :min karakter.',
    'max'      => ':attribute maksimal :max karakter.',
    'confirmed'=> 'Konfirmasi :attribute tidak cocok.',

    /*
    |--------------------------------------------------------------------------
    | Nama Atribut Kustom
    |--------------------------------------------------------------------------
    | Agar field seperti "email" diganti menjadi "Alamat Email" di pesan error.
    */
    'attributes' => [
        'email'    => 'Username/NIP',
        'password' => 'Kata sandi',
    ],
];
