<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontakPesan extends Model
{
    protected $table = 'kontak_pesan'; // WAJIB — Laravel default-nya cari "kontak_pesans"

    protected $fillable = [
        'nama', 'instansi', 'email', 'subjek', 'pesan', 'dibaca',
    ];
}