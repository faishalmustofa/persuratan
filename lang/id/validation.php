<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ' :attribute harus diterima.',
    'accepted_if' => ' :attribute harus diterima jika :other adalah :value.',
    'active_url' => ' :attribute bukan URL yang valid.',
    'after' => ' :attribute harus tanggal setelah :date.',
    'after_or_equal' => ' :attribute harus tanggal setelah atau sama dengan :date.',
    'alpha' => ' :attribute hanya boleh berisi huruf.',
    'alpha_dash' => ' :attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => ' :attribute hanya boleh berisi huruf dan angka.',
    'array' => ' :attribute harus berupa array.',
    'ascii' => ' :attribute hanya boleh berisi karakter dan simbol alfanumerik satu-byte.',
    'before' => ' :attribute harus tanggal sebelum :date.',
    'before_or_equal' => ' :attribute harus tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => ' :attribute harus memiliki antara :min dan :max item.',
        'file' => ' :attribute harus berada di antara :min dan :max kilobytes.',
        'numeric' => ' :attribute harus berada di antara :min dan :max.',
        'string' => ' :attribute harus berada di antara :min dan :max characters.',
    ],
    'boolean' => ' :attribute harus bernilai benar atau salah.',
    'confirmed' => ':attribute konfirmasi tidak cocok.',
    'current_password' => 'Kata sandi salah.',
    'date' => ' :attribute bukan merupakan tanggal yang valid.',
    'date_equals' => ' :attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => ' :attribute tidak sesuai dengan format :format.',
    'decimal' => ' :attribute harus memiliki :decimal angka desimal.',
    'declined' => ' :attribute harus ditolak.',
    'declined_if' => ' :attribute harus ditolak jika :other adalah :value.',
    'different' => ' :attribute dan :other harus berbeda.',
    'digits' => ' :attribute harus :digits digit Angka.',
    'digits_between' => ' :attribute harus berada di antara :min dan :max digit angka.',
    'dimensions' => ' :attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => ' :attribute memiliki nilai duplikat.',
    'doesnt_end_with' => ' :attribute tidak boleh diakhiri dengan salah satu dari berikut ini: :values.',
    'doesnt_start_with' => ' :attribute mungkin tidak dimulai dengan salah satu dari berikut ini: :values.',
    'email' => ' :attribute harus berupa alamat email yang valid.',
    'ends_with' => ' :attribute harus diakhiri dengan salah satu dari berikut ini: :values.',
    'enum' => ' yang dipilih :attribute tidak valid.',
    'exists' => ' yang dipilih :attribute tidak valid.',
    'file' => ' :attribute harus berupa file.',
    'filled' => ' :attribute bidang harus memiliki nilai.',
    'gt' => [
        'array' => ' :attribute harus memiliki lebih dari :value items.',
        'file' => ' :attribute harus memiliki lebih dari :value kilobytes.',
        'numeric' => ' :attribute harus memiliki lebih dari :value.',
        'string' => ' :attribute harus memiliki lebih dari :value characters.',
    ],
    'gte' => [
        'array' => ' :attribute harus memiliki :value items or more.',
        'file' => ' :attribute harus lebih besar atau sama dengan :value kilobytes.',
        'numeric' => ' :attribute harus lebih besar atau sama dengan :value.',
        'string' => ' :attribute harus lebih besar atau sama dengan :value karakter.',
    ],
    'image' => ' :attribute harus berupa gambar.',
    'in' => ' yang dipilih :attribute tidak valid.',
    'in_array' => ' :attribute bidang tidak ada di :other.',
    'integer' => ' :attribute harus berupa integer.',
    'ip' => ' :attribute harus berupa alamat IP yang valid.',
    'ipv4' => ' :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => ' :attribute harus berupa alamat IPv6 yang valid.',
    'json' => ' :attribute harus berupa JSON string.',
    'lowercase' => ' :attribute harus menggunakan huruf kecil.',
    'lt' => [
        'array' => ' :attribute harus memiliki kurang dari :value item.',
        'file' => ' :attribute harus kurang dari :value kilobytes.',
        'numeric' => ' :attribute harus kurang dari :value.',
        'string' => ' :attribute harus kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => ' :attribute tidak boleh lebih dari :value item.',
        'file' => ' :attribute harus kurang dari atau sama dengan :value kilobytes.',
        'numeric' => ' :attribute harus kurang dari atau sama dengan :value.',
        'string' => ' :attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => ' :attribute harus merupakan alamat MAC yang valid.',
    'max' => [
        'array' => ' :attribute tidak boleh lebih dari :max item.',
        'file' => ' :attribute tidak boleh lebih besar dari :max kilobytes.',
        'numeric' => ' :attribute tidak boleh lebih besar dari :max.',
        'string' => ' :attribute tidak boleh lebih besar dari :max karakter.',
    ],
    'max_digits' => ' :attribute tidak boleh memiliki lebih dari :max digits.',
    'mimes' => ' :attribute harus berupa file dengan tipe: :values.',
    'mimetypes' => ' :attribute harus berupa file dengan tipe: :values.',
    'min' => [
        'array' => ' :attribute harus memiliki setidaknya :min item.',
        'file' => ' :attribute harus memiliki setidaknya :min kilobytes.',
        'numeric' => ' :attribute harus memiliki setidaknya :min.',
        'string' => ' :attribute harus memiliki setidaknya :min karakter.',
    ],
    'min_digits' => ' :attribute harus memiliki setidaknya :min digit.',
    'missing' => ' kolom :attribute harus tidak diisi.',
    'missing_if' => ' kolom :attribute harus hilang ketika :other adalah :value.',
    'missing_unless' => ' kolom :attribute harus hilang kecuali :other adalah :value.',
    'missing_with' => ' kolom :attribute harus hilang ketika :values ada.',
    'missing_with_all' => ' :attribute harus hilang ketika :values ada.',
    'multiple_of' => ' :attribute harus berupa kelipatan of :value.',
    'not_in' => ' yang dipilih :attribute tidak valid.',
    'not_regex' => ' :attribute format tidak valid.',
    'numeric' => ' :attribute harus berupa angka.',
    'password' => [
        'letters' => ' :attribute harus mengandung setidaknya satu huruf.',
        'mixed' => ' :attribute harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => ' :attribute harus berisi setidaknya satu angka.',
        'symbols' => ' :attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih yang berbeda :attribute.',
    ],
    'present' => ' kolom :attribute harus ada.',
    'prohibited' => ' kolom :attribute dilarang.',
    'prohibited_if' => ' kolom :attribute dilarang jika :other adalah :value.',
    'prohibited_unless' => ' kolom :attribute dilarang kecuali jika :other ada di :values.',
    'prohibits' => ' kolom :attribute dilarang :other dari kehadirannya.',
    'regex' => ' :attribute format tidak valid.',
    'required' => ' kolom :attribute wajib diisi.',
    'required_array_keys' => ' kolom :attribute harus berisi entri untuk: :values.',
    'required_if' => ' kolom :attribute diperlukan ketika :other adalah :value.',
    'required_if_accepted' => ' kolom :attribute diperlukan ketika :other diterima.',
    'required_unless' => ' kolom :attribute diperlukan kecuali jika :other ada di :values.',
    'required_with' => ' kolom :attribute diperlukan ketika :values ada.',
    'required_with_all' => ' kolom :attribute diperlukan ketika :values ada.',
    'required_without' => ' kolom :attribute diperlukan ketika :values tidak ada.',
    'required_without_all' => ' kolom :attribute diperlukan ketika tidak ada satupun dari :values ada.',
    'same' => ' :attribute dan :or harus sama.',
    'size' => [
        'array' => ' :attribute harus berisi :size item.',
        'file' => ' :attribute harus :size kilobytes.',
        'numeric' => ' :attribute harus :size.',
        'string' => ' :attribute harus :size karakter.',
    ],
    'starts_with' => ' :attribute harus dimulai dengan salah satu dari following: :values.',
    'string' => ' :attribute harus berupa string.',
    'timezone' => ' :attribute harus merupakan zona waktu yang valid.',
    'unique' => ' :attribute telah diambil.',
    'uploaded' => ' :attribute gagal mengunggah.',
    'uppercase' => ' :attribute harus menggunakan huruf besar.',
    'url' => ' :attribute harus berupa URL yang valid.',
    'ulid' => ' :attribute harus merupakan ULID yang valid.',
    'uuid' => ' :attribute harus berupa UUID yang masih berlaku.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
