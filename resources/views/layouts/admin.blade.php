<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — Pusdatin BNPT</title>
    <link rel="stylesheet" href="{{ asset('css/pusdatin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @stack('styles')
</head>
<body>
    <div class="admin-wrap">
        {{-- ===== SIDEBAR ADMIN ===== --}}
        <aside class="admin-side">
            <div class="admin-logo">
                <div class="kotak">
                    <img src="{{ asset('images/logo-bnpt.png') }}" alt="Logo BNPT">
                </div>
                <div>
                    <b style="color:#fff;">PUSDATIN BNPT</b><br>
                    <small style="color:#888;">Panel Administrator</small>
                </div>
            </div>

            <div class="grup">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'aktif' : '' }}">&#9638; Dashboard</a>

            <div class="grup">Layanan Pengaduan</div>
            <a href="{{ route('admin.tiket.index') }}" class="{{ request()->routeIs('admin.tiket.*') ? 'aktif' : '' }}">&#9432; Tiket Pengaduan</a>

           <div class="grup">Publikasi & Konten</div>
            <a href="{{ route('admin.pengumuman.index') }}" class="{{ request()->routeIs('admin.pengumuman.*') ? 'aktif' : '' }}">&#128196; Informasi / Pengumuman</a>
            <a href="{{ route('admin.sop.index') }}" class="{{ request()->routeIs('admin.sop.*') ? 'aktif' : '' }}">&#128737; Dokumen SOP</a>
            <a href="{{ route('admin.kontak.index') }}" class="{{ request()->routeIs('admin.kontak.*') ? 'aktif' : '' }}">
                &#9993; Pesan Kontak
                @php($baru = \App\Models\KontakPesan::where('dibaca', false)->count())
                @if ($baru > 0)
                    <span style="background:#c8102e; color:#fff; border-radius:10px; padding:1px 8px; font-size:.75rem;">
                        {{ $baru }}
                    </span>
                @endif
            </a>

            <div class="grup">Konten Website</div>
            @foreach (\App\Models\ContentGroup::orderBy('urutan')->get() as $g)
            <a href="{{ route('admin.konten.edit', $g) }}" class="{{ request()->routeIs('admin.konten.edit') && request()->route('group')?->slug === $g->slug ? 'aktif' : '' }}">
                &#9998; {{ $g->label }}
            </a>
            @endforeach

            <div class="grup">Lainnya</div>
            <a href="{{ route('beranda') }}" target="_blank">&#8599; Lihat Situs Publik</a>
            
        <div class="admin-sidebar-bottom">

    <div class="admin-sidebar-user">
        <span class="user-icon">&#128100;</span>
        <span>{{ auth()->user()->nama }}</span>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" class="admin-sidebar-logout">
            Keluar
        </button>
    </form>

        </div>
        </aside>

<div class="admin-overlay" id="adminOverlay"></div>
        <div class="admin-main">
            <header class="admin-top">

    <div class="admin-page-info">
        <strong>
            @yield('page-title', 'Dashboard')
        </strong>
        <br>
        <small>
            {{ now()->translatedFormat('l, d F Y') }}
        </small>
    </div>

    <div class="admin-mobile-logo">
        <div class="kotak">
            <img src="{{ asset('images/logo-bnpt.png') }}" alt="Logo BNPT">
        </div>
        <div>
            <b>PUSDATIN BNPT</b>
            <small>Panel Administrator</small>
        </div>
    </div>

    <div class="admin-header-right">

        <div class="drop" id="notif-drop">

            <button
                type="button"
                class="notif-button"
                aria-label="Notifikasi"
            >
                &#128276;

                @if (auth()->user()->unreadNotifications->count())
                    <span class="notif-dot">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

            <div class="drop-menu">

                @forelse (auth()->user()->notifications()->latest()->take(6)->get() as $n)

                    <div
                        class="item"
                        style="{{ $n->read_at ? '' : 'background:#fbf1f3;' }}"
                    >
                        {{ $n->data['pesan'] ?? 'Notifikasi' }}
                        <br>

                        <small>
                            {{ $n->created_at->diffForHumans() }}
                        </small>
                    </div>

                @empty

                    <div class="item">
                        Belum ada notifikasi.
                    </div>

                @endforelse

            </div>
        </div>


        <div class="admin-user-header">
    <span>
        &#128100; {{ auth()->user()->nama }}
    </span>

    <span class="badge badge-ditolak">
        ADMIN
    </span>
        </div>

        <form
            method="POST"
            action="{{ route('logout') }}"
            class="admin-logout-header"
        >
            @csrf

            <button type="submit">
                Keluar
            </button>
        </form>


        {{-- Hamburger --}}
        <button
            type="button"
            class="admin-hamburger"
            id="adminHamburger"
            aria-label="Menu"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>

</header>

            <main class="admin-body">
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if (session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif
                @yield('content')
            </main>
        </div>
    </div>

    <script>

    const notifDrop = document.getElementById('notif-drop');

    if (notifDrop) {

        const notifButton = notifDrop.querySelector('button');

        notifButton.addEventListener('click', function () {

            notifDrop.classList.toggle('open');

        });
    }

    const adminHamburger =
        document.getElementById('adminHamburger');

    const adminSidebar =
        document.querySelector('.admin-side');

    const adminOverlay =
        document.getElementById('adminOverlay');


    adminHamburger.addEventListener('click', function () {

        adminSidebar.classList.toggle('open');

        adminOverlay.classList.toggle('open');

        this.classList.toggle('active');


        if (adminSidebar.classList.contains('open')) {

            document.body.style.overflow = 'hidden';

        } else {

            document.body.style.overflow = '';

        }

    });

    adminOverlay.addEventListener('click', function () {

        adminSidebar.classList.remove('open');

        adminOverlay.classList.remove('open');

        adminHamburger.classList.remove('active');

        document.body.style.overflow = '';

    });

    document.querySelectorAll('.admin-side a').forEach(function (link) {

        link.addEventListener('click', function () {

            if (window.innerWidth <= 768) {

                adminSidebar.classList.remove('open');

                adminOverlay.classList.remove('open');

                adminHamburger.classList.remove('active');

                document.body.style.overflow = '';

            }

        });

    });
</script>
</body>
</html>
