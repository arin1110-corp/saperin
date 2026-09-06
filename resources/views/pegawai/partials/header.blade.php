@php
    $headerFoto = $user?->foto?->thumbnail_url;
    $headerNama = trim(
        ($user?->user_gelardepan ? $user->user_gelardepan . ' ' : '') .
            ($user?->user_nama ?? '') .
            ($user?->user_gelarlbelakang ? ', ' . $user->user_gelarlbelakang : ''),
    );

    if ($headerNama === '') {
        $headerNama = 'Pegawai';
    }
@endphp

<header class="samperin-header">

    <div class="samperin-header-inner">

        <a href="{{ route('pegawai.index') }}" class="samperin-brand">

            <img src="{{ asset('assets/images/logo-samperin-full.png') }}" class="samperin-brand-logo" alt="SAMPERIN"
                onerror="this.style.display='none'">
        </a>
        <button type="button" class="samperin-header-role" data-bs-toggle="modal" data-bs-target="#modalPilihRole">

            <i class="bi bi-person-circle"></i>

            <span>{{ session('samperin_role_nama', 'Pegawai') }}</span>
        </button>

        @include('pegawai.partials.nav')

        <div class="samperin-account">

            <div class="samperin-notification">
                <i class="bi bi-bell"></i>

                @if (isset($permintaanAktif) && $permintaanAktif->isNotEmpty())
                    <span class="samperin-notification-dot"></span>
                @endif
            </div>

            <div class="dropdown">

                <button type="button" class="samperin-user-menu" data-bs-toggle="dropdown" aria-expanded="false">

                    @if ($headerFoto)
                        <img src="{{ $headerFoto }}" class="samperin-user-photo" alt="Foto Pegawai">
                    @else
                        <div class="samperin-user-photo-placeholder">
                            <i class="bi bi-person"></i>
                        </div>
                    @endif

                    <div class="samperin-user-info">

                        <div class="samperin-user-name">
                            {{ $headerNama }}
                        </div>

                        <div class="samperin-user-role">
                            {{ session('samperin_role_nama', 'Pegawai') }}
                        </div>

                    </div>

                    <i class="bi bi-chevron-down samperin-user-chevron"></i>

                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                    <li>
                        <a class="dropdown-item" href="{{ route('pegawai.index') }}">
                            <i class="bi bi-person me-2"></i>
                            Profil
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ route('pegawai.berkas') }}">
                            <i class="bi bi-folder2-open me-2"></i>
                            Berkas
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form method="POST" action="{{ route('samperin.logout') }}">
                            @csrf

                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Keluar
                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        </div>

    </div>

</header>
