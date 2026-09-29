@extends('layouts.admin')

@section('title', 'Pesan Kontak')

@section('page-title', 'Kotak Masuk Kontak')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-informasi.css') }}">
@endpush

@section('content')

    <div class="ringkasan">

        <div class="kartu">
            <b>{{ $total }}</b>
            Total Pesan
            <br>
            <small>Masuk lewat form kontak</small>
        </div>

    </div>

    <div class="panel">

        <div class="dashboard-panel-header">
            <h3>Pesan Masuk</h3>
        </div>

        <div class="dashboard-table-wrapper">

            <table class="tabel">

                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Instansi</th>
                        <th>Email</th>
                        <th>Subjek</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($pesan as $p)

                        <tr style="{{ $p->dibaca ? '' : 'font-weight:600;' }}">

                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->instansi ?: '-' }}</td>
                            <td>{{ $p->email }}</td>
                            <td>{{ Str::limit($p->subjek, 40) }}</td>

                            <td>
                                @if ($p->dibaca)
                                    <span class="badge" style="background:#e9ecef; color:#555;">
                                        Sudah dibaca
                                    </span>
                                @else
                                    <span class="badge" style="background:#fff3cd; color:#8a6d00;">
                                        Baru
                                    </span>
                                @endif
                            </td>

                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>

                           <td>
    <div class="aksi-tabel">
        <a href="{{ route('admin.kontak.show', $p) }}"
           class="btn-aksi btn-detail"
           title="Lihat Pesan">
            <i class="bi bi-eye"></i>
        </a>

        <form method="POST" 
                                          action="{{ route('admin.kontak.destroy', $p) }}" 
                                          style="display:inline;" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" 
                                                class="btn-aksi btn-hapus" 
                                                title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
    </div>
</td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" style="text-align:center; color:#888;">
                                Belum ada pesan masuk.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{ $pesan->links() }}

    </div>

@endsection