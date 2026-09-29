<?php

namespace App\Http\Controllers;

use App\Models\KontakPesan;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    public function kirimKontak(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'instansi' => ['nullable', 'string', 'max:150'],
            'email'    => ['required', 'email', 'max:150'],
            'subjek'   => ['required', 'string', 'max:150'],
            'pesan'    => ['required', 'string', 'max:5000'],
        ]);

        KontakPesan::create($data);

        return back()->with(
            'success',
            'Pesan berhasil dikirim. Terima kasih!'
        );
    }
}