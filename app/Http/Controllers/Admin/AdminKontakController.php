<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontakPesan;

class AdminKontakController extends Controller
{
    public function index()
    {
        $pesan = KontakPesan::latest()->paginate(15);
        $total = KontakPesan::count();
        $belumBaca = KontakPesan::where('dibaca', false)->count();

        return view('admin.kontak.index', compact(
            'pesan',
            'total',
            'belumBaca'
        ));
    }

    public function show(KontakPesan $kontak)
    {
        if (! $kontak->dibaca) {
            $kontak->update(['dibaca' => true]);
        }

        return view('admin.kontak.show', compact('kontak'));
    }

    public function destroy(KontakPesan $kontak)
    {
        $kontak->delete();

        return redirect()
            ->route('admin.kontak.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}