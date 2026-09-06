<nav class="samperin-header-nav">

    <a href="{{ route('pegawai.index') }}"
        class="samperin-nav-item {{ request()->routeIs('pegawai.index', 'pegawai.profil') ? 'active' : '' }}">
        <i class="bi bi-person"></i>
        <span>Profil</span>
    </a>

    <a href="{{ route('pegawai.berkas') }}"
        class="samperin-nav-item {{ request()->routeIs('pegawai.berkas') ? 'active' : '' }}">
        <i class="bi bi-folder2"></i>
        <span>Berkas</span>
    </a>

</nav>
