@extends('layouts.portal')
@section('title', 'Buat Tiket — Layanan Akun Email')

@section('content')
<div class="panel">
    <a href="{{ route('portal.tiket.create') }}" style="color:var(--merah); text-decoration:none; font-size:.9rem; display:inline-block; margin-bottom:16px;">← Kembali Pilih Kategori</a>

    <div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
        <div class="ikon-wrap" style="width:56px; height:56px; background:#fdf2f4; color:var(--merah); border-radius:16px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <div>
            <h2 style="font-size:1.4rem; font-weight:700; margin:0;">Layanan Akun Email</h2>
            <p style="color:#666; font-size:.9rem; margin:2px 0 0;">Meliputi aduan dan permintaan terkait akun surat elektronik (email) resmi.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('portal.tiket.store', 'email') }}" enctype="multipart/form-data" class="stack">
        @csrf
        <input type="hidden" name="subjek" value="Permohonan/Aduan Layanan Akun Email">

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
            <div>
                <label>Nama Lengkap <span class="req">*</span></label>
                <input type="text" name="detail[nama_pelapor]" value="{{ old('detail.nama_pelapor', auth()->user()->nama) }}" placeholder="Tuliskan Nama Lengkap" required>
            </div>
            <div>
                <label>Unit Kerja <span class="req">*</span></label>
                <input type="text" name="detail[unit_kerja]" value="{{ old('detail.unit_kerja') }}" placeholder="Tuliskan Unit Kerja" required>
            </div>
            <div>
                <label>Jabatan <span class="req">*</span></label>
                <input type="text" name="detail[jabatan]" value="{{ old('detail.jabatan') }}" placeholder="Tuliskan Jabatan" required>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
            <div>
                <label>Gedung <span class="req">*</span></label>
                <select name="detail[gedung]" required class="select-placeholder" onchange="this.classList.toggle('has-value', !!this.value)">
                    <option value="" disabled selected hidden>Pilih Gedung</option>
                    <option value="Gedung Utama" style="color: #333;">Biwara</option>
                    <option value="Gedung B" style="color: #333;">Baladika</option>
                    <option value="Gedung C" style="color: #333;">Pasopati</option>
                    <option value="Gedung Utama" style="color: #333;">Nenggala</option>
                    <option value="Gedung B" style="color: #333;">Rumga</option>
                    <option value="Gedung C" style="color: #333;">Lapas</option>
                    <option value="Gedung Utama" style="color: #333;">BLK</option>
                    <option value="Gedung B" style="color: #333;">Asrama Lepas</option>
                    <option value="Gedung C" style="color: #333;">Museum</option>
                    <option value="Gedung Utama" style="color: #333;">Data Center</option>
                    <option value="Gedung B" style="color: #333;">Wisman</option>
                    <option value="Gedung C" style="color: #333;">Hayam Wuruk</option>
                    <option value="Gedung B" style="color: #333;">Royal</option>
                    <option value="Gedung C" style="color: #333;">Tomang</option>
                </select>
            </div>
            <div>
                <label>Lantai / Ruangan <span class="req">*</span></label>
                <input type="text" name="detail[ruangan]" value="{{ old('detail.ruangan') }}" placeholder="Tuliskan Lantai / Ruangan" required>
            </div>
            <div>
                <label>No Handphone (WhatsApp aktif) <span class="req">*</span></label>
                <input type="text" name="detail[no_hp]" value="{{ old('detail.no_hp') }}" placeholder="Tuliskan Handphone" required>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
            <div>
                <label>Nomor BMN <span class="req">*</span></label>
                <input type="text" name="detail[no_bmn]" value="{{ old('detail.no_bmn') }}" placeholder="Tuliskan Nomor BMN" required>
            </div>
        </div>

        <div style="border:1px solid #e5e7eb; border-radius:16px; padding:20px; margin-top:20px; background:#fafafa;">
            <div style="margin-bottom:16px;">
                <label>Subjek Pengaduan / Permohonan <span class="req">*</span></label>
                <input type="text" name="subjek" value="{{ old('subjek') }}" placeholder="Tuliskan Subjek Pengaduan / Permohonan" required style="width:100%; box-sizing:border-box;">
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                <div>
                    <label>Nama Pemilik Akun <span class="req">*</span></label>
                    <input type="text" name="detail[nama_pemilik]" value="{{ old('detail.nama_pemilik') }}" placeholder="Tuliskan Nama Lengkap" required>
                </div>
                <div>
                    <label>Jabatan Pemilik Akun <span class="req">*</span></label>
                    <input type="text" name="detail[jabatan_pemilik]" value="{{ old('detail.jabatan_pemilik') }}" placeholder="Tuliskan Jabatan Pemilik Akun" required>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-top:16px; align-items:start;">
                <div>
                    <label>Deskripsi Gangguan atau Permintaan Akun <span class="req">*</span></label>
                    <textarea name="detail[deskripsi]" rows="4" placeholder="Jelaskan Gangguan/ Permintaan Akun" required style="width:100%; height:120px; resize:none; margin-top:4px;">{{ old('detail.deskripsi') }}</textarea>
                </div>

                <div>
                    <label>Upload Screenshot Laman MyASN</label>
                    <p style="font-size:.82rem; color:red; margin:4px 0 8px; line-height:1.4;">
                        *Unggah screenshot jika status kepegawaian Anda adalah <strong>ASN</strong>.
                    </p>
                    <div style="margin-top:6px;">
                        <input type="file" name="lampiran" accept=".pdf,.png" style="width:auto; padding:6px; background:#fff; border:1px solid #ccc; border-radius:6px;">
                        <small style="color:#888; display:block; margin-top:4px;">Maksimal 5 MB (Hanya PDF, PNG)</small>
                    </div>
                </div>
            </div>
</div>

        <button type="submit" class="btn-merah" style="margin-top:24px; padding:12px 32px; font-size:1rem;">Kirim Tiket Permohonan</button>
    </form>
</div>
@endsection