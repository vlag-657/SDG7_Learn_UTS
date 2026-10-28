<header class="site-header">
    <a class="brand" href="{{ route('home') }}" aria-label="SDG7Learn, beranda">
        <span class="brand-mark" aria-hidden="true">
            <svg viewBox="0 0 32 32" fill="none">
                <path d="M17.2 3.5 7.4 17h7l-.9 11.5L24.6 14h-7.1l-.3-10.5Z" fill="currentColor"/>
                <path d="M5 25.5c2.4 1.8 5.4 2.8 8.7 2.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </span>
        <span class="brand-name">SDG7<span>Learn</span></span>
    </a>

    <button class="menu-toggle" type="button" aria-label="Buka navigasi" aria-expanded="false" data-menu-toggle>
        <span></span><span></span>
    </button>

    <nav class="main-nav" aria-label="Navigasi utama" data-navigation>
        <a href="{{ route('artikel.index') }}" class="{{ request()->routeIs('artikel.*') ? 'active' : '' }}">Artikel</a>
        <a href="{{ route('kuis.index') }}" class="{{ request()->routeIs('kuis.*') ? 'active' : '' }}">Kuis</a>
        <a href="{{ route('forum.index') }}" class="{{ request()->routeIs('forum.*') ? 'active' : '' }}">Forum</a>
        
        @auth
            <a href="{{ route('hasil-kuis.index') }}" class="{{ request()->routeIs('hasil-kuis.*') ? 'active' : '' }}">Hasil Belajar</a>
            <a href="{{ route('call-center.index') }}" class="{{ request()->routeIs('call-center.*') ? 'active' : '' }}">Call Center</a>
            
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin*') ? 'active' : '' }}" style="color:#d97706; font-weight:700;">★ Panel Admin</a>
            @endif
        @endauth
    </nav>

    <div class="header-actions">
        @guest
            <a class="button button-quiet button-small" href="{{ route('login') }}">Masuk</a>
            <a class="button button-primary button-small" href="{{ route('register') }}">Daftar gratis <span aria-hidden="true">↗</span></a>
        @else
            <div style="display:flex; align-items:center; gap:8px;">
                <a href="{{ route('profil.index') }}" class="button button-quiet button-small" title="Lihat Profil">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:50%; background:var(--lime); color:var(--deep-green); font-size:10px; font-weight:800; margin-right:4px;">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
                    </span>
                    <span>{{ Str::limit(Auth::user()->nama, 12) }}</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" style="margin:0; display:inline;">
                    @csrf
                    <button type="submit" class="button button-quiet button-small" title="Keluar dari akun" style="color:#a4261d; border-color:#fecdd3;" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        @endguest
    </div>
</header>
