@extends('layouts.admin')
@section('title', 'Edit Konten')
@section('page-title', 'Edit Konten — ' . $group->label)

@section('content')
<div style="display:grid; grid-template-columns:260px 1fr; gap:24px; align-items:start;">

    {{-- Panel Kiri: Navigasi Section --}}
    <div class="panel">
        <h4 style="margin-bottom:12px;">Section</h4>
        <div style="display:flex; flex-direction:column; gap:4px;">
            @foreach ($groups as $g)
                <a href="{{ route('admin.konten.edit', $g) }}"
                   style="display:block; padding:8px 10px; border-radius:6px; text-decoration:none; font-size:.9rem;
                          color:{{ $g->id === $group->id ? '#fff' : '#333' }};
                          background:{{ $g->id === $group->id ? 'var(--merah)' : 'transparent' }};">
                    {{ $g->label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Panel Kanan: Isi Konten --}}
    <div style="display:flex; flex-direction:column; gap:24px;">

        @if ($group->slug === 'mitra')
            {{-- =========================================
                 KHUSUS TAMPILAN SECTION MITRA
               ========================================= --}}

            {{-- 1. Kartu Daftar Mitra yang Sudah Ada --}}
            <div class="panel">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <div>
                        <h4 style="margin:0; font-size:1.1rem;">Daftar Mitra Pusdatin</h4>
                        <small style="color:#777;">Kelola nama dan logo mitra yang tampil di situs publik.</small>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.konten.update', $group) }}" class="stack" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div style="display:flex; flex-direction:column; gap:16px;">
                        @php
                            // Kelompokkan field berdasarkan nomor urut mitra (misal: nama_1 & logo_1)
                            $fieldsGrouped = $group->fields->groupBy(function($item) {
                                preg_match('/\d+$/', $item->key, $matches);
                                return $matches[0] ?? $item->id;
                            });
                        @endphp

                        @forelse ($fieldsGrouped as $index => $fields)
                            <div style="background:#fafafa; border:1px solid #e5e7eb; border-radius:12px; padding:16px; display:flex; gap:16px; align-items:center; flex-wrap:wrap; position:relative;">
                                
                                {{-- Preview Logo --}}
                                @php
                                    $logoField = $fields->firstWhere('type', 'image');
                                    $namaField = $fields->firstWhere('type', '!=', 'image');
                                @endphp

                                <div style="width:90px; height:90px; background:#fff; border:1px solid #e0e0e0; border-radius:8px; display:flex; align-items:center; justify-content:center; padding:6px; flex-shrink:0;">
                                    @if ($logoField && isset($values[$logoField->id]) && $values[$logoField->id])
                                        <img src="{{ asset('storage/'.$values[$logoField->id]) }}" alt="Logo" style="max-width:100%; max-height:100%; object-fit:contain;">
                                    @else
                                        <span style="font-size:0.75rem; color:#aaa; text-align:center;">Tidak ada logo</span>
                                    @endif
                                </div>

                                {{-- Input Nama & File --}}
                                <div style="flex:1; min-width:240px; display:flex; flex-direction:column; gap:8px;">
                                    @if ($namaField)
                                        <div>
                                            <label style="font-size:0.82rem; color:#666; margin-bottom:2px; display:block;">Nama Mitra #{{ $index }}</label>
                                            <input type="text" name="fields[{{ $namaField->id }}]" value="{{ old("fields.$namaField->id", $values[$namaField->id] ?? '') }}" placeholder="Nama Mitra" maxlength="50">
                                        </div>
                                    @endif

                                    @if ($logoField)
                                        <div>
                                            <label style="font-size:0.82rem; color:#666; margin-bottom:2px; display:block;">Ganti Logo (Optional)</label>
                                            <input type="file" name="fields[{{ $logoField->id }}]" accept="image/*" style="font-size:0.82rem;">
                                        </div>
                                        {{-- Tombol Hapus --}}
                                        <div style="width:100%; display:flex; justify-content:flex-end; margin-top:12px;">
                                            <button type="button"
                                                    onclick="hapusMitra({{ $index }})"
                                                    style="
                                                        background:#fff;
                                                        color:#dc2626;
                                                        border:1px solid #dc2626;
                                                        padding:8px 16px;
                                                        border-radius:6px;
                                                        font-size:.82rem;
                                                        cursor:pointer;
                                                    ">
                                                🗑 Hapus Mitra
                                            </button>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <p style="color:#999; text-align:center; padding:20px 0;">Belum ada data mitra.</p>
                        @endforelse
                    </div>

                    @if ($group->fields->count() > 0)
                        <button type="submit" class="btn-merah" style="margin-top:20px;">
                            Simpan Perubahan Mitra
                        </button>
                    @endif
                </form>

                                {{-- Form hapus mitra dibuat di luar form simpan --}}
                @foreach ($fieldsGrouped as $index => $fields)
                    <form id="form-hapus-mitra-{{ $index }}"
                        method="POST"
                        action="{{ route('admin.konten.mitra.destroy', [$group, $index]) }}"
                        style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            </div>

            {{-- 2. Kartu Form Tambah Mitra Baru --}}
            <div class="panel" style="border-left: 4px solid var(--merah);">
                <h4 style="margin-bottom:4px; font-size:1.05rem;">+ Tambah Mitra Baru</h4>
                <p style="color:#666; font-size:.85rem; margin-bottom:16px;">
                    Isi nama dan unggah logo mitra baru untuk ditambahkan ke daftar.
                </p>

                <form method="POST"
                      action="{{ route('admin.konten.mitra.store', $group) }}"
                      class="stack"
                      style="max-width:500px;"
                      enctype="multipart/form-data">
                    @csrf

                    <label>Nama Mitra <span style="color:var(--merah);">*</span></label>
                    <input type="text" name="nama" placeholder="Contoh: Kementerian Komunikasi dan Digital" maxlength="50" required>

                    <label>Logo Mitra <span style="color:var(--merah);">*</span></label>
                    <input type="file" name="logo" accept="image/*" required>

                    <button type="submit" class="btn-merah" style="margin-top:12px;">
                        + Tambah Mitra Baru
                    </button>
                </form>
            </div>

        @else
            {{-- =========================================
                 TAMPILAN SECTION KONTEN LAINNYA (STANDARD)
               ========================================= --}}
            <div class="panel">
                <form method="POST" action="{{ route('admin.konten.update', $group) }}" class="stack" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @forelse ($group->fields as $field)
                        <div style="border:1px solid #eee; border-radius:8px; padding:14px; margin-bottom:12px; background:#fff;">
                            <label style="font-weight:600; margin-bottom:6px; display:block;">{{ $field->label }}</label>

                            @if ($field->type === 'textarea')
                                <textarea name="fields[{{ $field->id }}]" rows="4">{{ old("fields.$field->id", $values[$field->id]) }}</textarea>
                            @elseif ($field->type === 'image')
                                @if ($values[$field->id])
                                    <img src="{{ asset('storage/'.$values[$field->id]) }}" style="max-width:160px; border-radius:6px; display:block; margin-bottom:8px;">
                                @endif
                                <input type="file" name="fields[{{ $field->id }}]" accept="image/*">
                            @else
                                <input type="text" name="fields[{{ $field->id }}]" value="{{ old("fields.$field->id", $values[$field->id]) }}">
                            @endif
                        </div>
                    @empty
                        <p style="color:#999;">Belum ada field di section ini.</p>
                    @endforelse

                    <button type="submit" class="btn-merah" style="margin-top:12px;">Simpan Perubahan</button>
                </form>
            </div>
        @endif

    </div>
</div>

<script>
function hapusMitra(index) {
    if (!confirm('Yakin ingin menghapus mitra ini? Data nama dan logo akan dihapus.')) {
        return;
    }

    document.getElementById('form-hapus-mitra-' + index).submit();
}
</script>
@endsection