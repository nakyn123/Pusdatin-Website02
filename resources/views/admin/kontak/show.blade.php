@extends('layouts.admin')

@section('title', 'Detail Pesan')

@section('page-title', 'Detail Pesan Kontak')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-informasi.css') }}">
@endpush

@section('content')

    <div style="margin-bottom: 16px;">
    <a href="{{ route('admin.kontak.index') }}" class="btn-kembali-admin">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>
</div>

    <div class="panel">

        <h3>{{ $kontak->subjek }}</h3>

        <p style="color:#666; margin-bottom:20px;">
            Dari <b>{{ $kontak->nama }}</b>
            ({{ $kontak->instansi ?: 'tanpa instansi' }})
            &bull; {{ $kontak->email }}
            &bull; {{ $kontak->created_at->format('d M Y H:i') }}
        </p>

        <div style="background:#f8f9fa; padding:16px; border-radius:8px; white-space:pre-line;">
            {{ $kontak->pesan }}
        </div>

        <div style="margin-top:20px; display:flex; gap:12px;">

            <a href="mailto:{{ $kontak->email }}?subject=Re: {{ $kontak->subjek }}"
   class="btn-outline-merah">
    Balas via Email
            </a>

        </div>

    </div>

@endsection