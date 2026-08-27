@php

    use Illuminate\Support\Facades\DB;

    /*
    |--------------------------------------------------------------------------
    | USER LOGIN
    |--------------------------------------------------------------------------
    */

    $headerUser = null;

    $sessionUserId = session('samperin_user_id');

    if ($sessionUserId) {
        $headerUser = \App\Models\SamperinUser::find($sessionUserId);
    }

    /*
    |--------------------------------------------------------------------------
    | FALLBACK SESSION USER
    |--------------------------------------------------------------------------
    */

    if (!$headerUser) {
        $headerUser = session('user_info') ?? session('user');
    }

    /*
    |--------------------------------------------------------------------------
    | DATA USER
    |--------------------------------------------------------------------------
    */

    $headerNama = $headerUser->user_nama ?? 'User';

    $headerNip = $headerUser->user_nip ?? '-';

    /*
    |--------------------------------------------------------------------------
    | FOTO USER
    |--------------------------------------------------------------------------
    */

    $headerFoto = null;

    if ($headerUser) {
        try {
            $foto = \App\Models\SamperinUserFoto::where('user_foto_user_uid', $headerUser->user_uid)->first();

            if ($foto && !empty($foto->user_foto_file)) {
                $headerFoto = $foto->user_foto_file;
            }
        } catch (\Throwable $e) {
            $headerFoto = null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FOTO URL
    |--------------------------------------------------------------------------
    */

    $headerFotoUrl = null;

    if ($headerFoto) {
        if (str_starts_with($headerFoto, 'http://') || str_starts_with($headerFoto, 'https://')) {
            $headerFotoUrl = $headerFoto;
        } else {
            $headerFotoUrl = asset(ltrim($headerFoto, '/'));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE AKTIF
    |--------------------------------------------------------------------------
    */

    $activeRole = session('active_role') ?? (session('role_slug') ?? 'admin');

    /*
    |--------------------------------------------------------------------------
    | AMBIL SEMUA ROLE USER
    |--------------------------------------------------------------------------
    |
    | JANGAN menggunakan role middleware di sini.
    |
    | Ambil langsung:
    |
    | samperin_user
    |       ↓
    | samperin_user_role
    |       ↓
    | samperin_role
    |
    */

    $userRoles = collect();

    if ($headerUser) {
        $userRoles = DB::table('samperin_user_role')

            ->join('samperin_role', 'samperin_role.role_uid', '=', 'samperin_user_role.user_role_role_uid')

            ->where('samperin_user_role.user_role_user_uid', $headerUser->user_uid)

            ->where('samperin_role.role_status', 1)

            ->select(
                'samperin_role.role_uid',
                'samperin_role.role_slug',
                'samperin_role.role_nama',
                'samperin_role.role_deskripsi',
                'samperin_role.role_status',
            )

            ->orderBy('samperin_role.role_nama')

            ->get();
    }

@endphp


<style>
    /* =====================================================
       HEADER
    ===================================================== */

    .admin-header {

        height: 82px;

        background: #ffffff;

        border-bottom:
            1px solid #e8ebf0;

        display: flex;

        align-items: center;

        justify-content: space-between;

        padding:
            0 32px;

        position: sticky;

        top: 0;

        z-index: 900;

    }


    .admin-header-breadcrumb {

        font-size: 10px;

        color: #9da5b1;

        margin-bottom: 6px;

    }


    .admin-header-breadcrumb strong {

        color: #c56b28;

    }


    .admin-header-title {

        font-size: 21px;

        font-weight: 750;

        letter-spacing: -.5px;

        color: #1a263b;

    }


    .admin-header-right {

        display: flex;

        align-items: center;

        gap: 14px;

    }


    /* =====================================================
       NOTIFICATION
    ===================================================== */

    .admin-notification {

        width: 40px;
        height: 40px;

        border-radius: 10px;

        background: #f6f7f9;

        color: #687386;

        display: flex;

        align-items: center;

        justify-content: center;

        position: relative;

        font-size: 15px;

    }


    .admin-notification-dot {

        position: absolute;

        right: 7px;

        top: 7px;

        width: 6px;

        height: 6px;

        border-radius: 50%;

        background: #d77d32;

        border:
            1px solid #fff;

    }


    /* =====================================================
       USER BUTTON
    ===================================================== */

    .admin-user-button {

        border: 0;

        background: transparent;

        cursor: pointer;

        display: flex;

        align-items: center;

        gap: 10px;

        padding: 4px 5px;

        border-radius: 11px;

    }


    .admin-user-button:hover {

        background: #f7f8fa;

    }


    .admin-user-info {

        text-align: right;

    }


    .admin-user-info strong {

        display: block;

        color: #202b40;

        font-size: 12px;

        font-weight: 700;

    }


    .admin-user-info span {

        display: block;

        color: #979fab;

        font-size: 9px;

        margin-top: 3px;

    }


    /* =====================================================
       AVATAR
    ===================================================== */

    .admin-user-avatar {

        width: 43px;

        height: 43px;

        border-radius: 11px;

        background:
            linear-gradient(135deg,
                #df873d,
                #b85c20);

        color: #fff;

        display: flex;

        align-items: center;

        justify-content: center;

        overflow: hidden;

        font-size: 13px;

        font-weight: 800;

    }


    .admin-user-avatar img {

        width: 100%;

        height: 100%;

        object-fit: cover;

    }


    /* =====================================================
       ROLE MODAL
    ===================================================== */

    .role-modal-overlay {

        position: fixed;

        inset: 0;

        background:
            rgba(10, 19, 34, .52);

        backdrop-filter: blur(4px);

        z-index: 5000;

        display: none;

        align-items: center;

        justify-content: center;

        padding: 20px;

    }


    .role-modal-overlay.show {

        display: flex;

    }


    .role-modal {

        width: 100%;

        max-width: 470px;

        background: #fff;

        border-radius: 17px;

        overflow: hidden;

        box-shadow:
            0 25px 70px rgba(0, 0, 0, .22);

    }


    /* =====================================================
       MODAL HEADER
    ===================================================== */

    .role-modal-header {

        padding:
            21px 22px;

        border-bottom:
            1px solid #edf0f3;

        display: flex;

        align-items: center;

        justify-content: space-between;

    }


    .role-modal-heading {

        display: flex;

        align-items: center;

        gap: 11px;

    }


    .role-modal-icon {

        width: 40px;

        height: 40px;

        border-radius: 10px;

        background: #fff0e3;

        color: #c66d2a;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 17px;

    }


    .role-modal-title {

        font-size: 15px;

        font-weight: 750;

        color: #1c273b;

    }


    .role-modal-desc {

        font-size: 9px;

        color: #9aa1ac;

        margin-top: 4px;

    }


    .role-close {

        width: 32px;

        height: 32px;

        border: 0;

        border-radius: 8px;

        background: #f5f6f8;

        color: #667084;

        cursor: pointer;

    }


    /* =====================================================
       MODAL BODY
    ===================================================== */

    .role-modal-body {

        padding: 19px;

        max-height: 450px;

        overflow-y: auto;

    }


    .role-modal-label {

        font-size: 9px;

        color: #9ba2ad;

        letter-spacing: .8px;

        margin:
            0 3px 10px;

    }


    /* =====================================================
       ROLE OPTION
    ===================================================== */

    .role-option {

        width: 100%;

        display: flex;

        align-items: center;

        gap: 11px;

        padding: 13px;

        border:
            1px solid #e7eaf0;

        border-radius: 11px;

        background: #fff;

        margin-bottom: 8px;

        color: #1e293d;

        transition: .15s ease;

        cursor: pointer;

        text-align: left;

    }


    .role-option:hover {

        background: #fff9f4;

        border-color: #e5bc98;

    }


    .role-option.active {

        background: #fff8f1;

        border-color: #e3b78e;

    }


    .role-option-icon {

        width: 38px;

        height: 38px;

        border-radius: 9px;

        background: #f3f5f7;

        display: flex;

        align-items: center;

        justify-content: center;

        color: #687386;

        font-size: 14px;

        flex-shrink: 0;

    }


    .role-option.active .role-option-icon {

        background: #ffe9d5;

        color: #c66d2a;

    }


    .role-option-text {

        flex: 1;

        min-width: 0;

    }


    .role-option-text strong {

        display: block;

        font-size: 12px;

        font-weight: 700;

    }


    .role-option-text span {

        display: block;

        margin-top: 4px;

        font-size: 9px;

        color: #969eaa;

    }


    .role-active {

        padding:
            5px 8px;

        border-radius: 6px;

        background: #eaf7ef;

        color: #318957;

        font-size: 8px;

        font-weight: 700;

        flex-shrink: 0;

    }


    /* =====================================================
       LOGOUT
    ===================================================== */

    .role-modal-footer {

        padding:
            0 19px 19px;

    }


    .role-logout {

        width: 100%;

        padding: 11px;

        border:
            1px solid #efd6d6;

        background: #fff6f6;

        color: #b64e4e;

        border-radius: 9px;

        cursor: pointer;

        font-size: 10px;

        font-weight: 700;

    }


    /* =====================================================
       MOBILE
    ===================================================== */

    @media(max-width:600px) {

        .admin-header {

            padding:
                0 18px;

        }


        .admin-user-info,
        .admin-notification {

            display: none;

        }

    }
</style>


<header class="admin-header">


    {{-- =================================================
         HEADER LEFT
    ================================================= --}}

    <div>

        <div class="admin-header-breadcrumb">

            SAMPERIN

            &nbsp;/&nbsp;

            <strong>

                Administrator

            </strong>

        </div>


        <div class="admin-header-title">

            Administrasi Sistem

        </div>

    </div>



    {{-- =================================================
         HEADER RIGHT
    ================================================= --}}

    <div class="admin-header-right">


        <div class="admin-notification">

            <i class="bi bi-bell"></i>

            <div class="admin-notification-dot"></div>

        </div>



        <button type="button" class="admin-user-button" onclick="openRoleModal()">

            <div class="admin-user-info">

                <strong>

                    {{ $headerNama }}

                </strong>


                <span>

                    {{ $headerNip }}

                </span>

            </div>



            <div class="admin-user-avatar">

                @if ($headerFotoUrl)
                    <img src="{{ $headerFotoUrl }}" alt="{{ $headerNama }}">
                @else
                    {{ strtoupper(substr(trim($headerNama), 0, 1)) }}
                @endif

            </div>

        </button>


    </div>


</header>



{{-- =========================================================
     MODAL GANTI ROLE
========================================================= --}}

<div class="role-modal-overlay" id="roleModal" onclick="closeRoleModalOutside(event)">


    <div class="role-modal">


        {{-- =================================================
             MODAL HEADER
        ================================================== --}}

        <div class="role-modal-header">


            <div class="role-modal-heading">


                <div class="role-modal-icon">

                    <i class="bi bi-person-gear"></i>

                </div>


                <div>

                    <div class="role-modal-title">

                        Ganti Role

                    </div>


                    <div class="role-modal-desc">

                        Pilih role untuk melanjutkan

                    </div>

                </div>


            </div>


            <button type="button" class="role-close" onclick="closeRoleModal()">

                <i class="bi bi-x-lg"></i>

            </button>


        </div>



        {{-- =================================================
             MODAL BODY
        ================================================== --}}

        <div class="role-modal-body">


            <div class="role-modal-label">

                ROLE TERSEDIA

            </div>



            @forelse($userRoles
                as $role)
                @php

                    $roleSlug = $role->role_slug ?? '';

                    $roleNama = $role->role_nama ?? ucfirst(str_replace(['-', '_'], ' ', $roleSlug));

                    $roleDeskripsi = $role->role_deskripsi ?? 'Akses SAMPERIN';

                    $isActive = $activeRole === $roleSlug;

                @endphp



                {{-- =========================================
                     SWITCH ROLE
                ========================================== --}}

                <form method="POST"
                    action="{{ route('samperin.role.switch') }}"
                    style="margin:0;">

                    @csrf


                    <input type="hidden" name="role" value="{{ $roleSlug }}">


                    <button type="submit"
                        class="
                            role-option
                            {{ $isActive ? 'active' : '' }}
                        ">


                        <div class="
                                role-option-icon
                            ">

                            @if (in_array($roleSlug, ['admin', 'administrator']))
                                <i
                                    class="
                                        bi
                                        bi-shield-lock-fill
                                    "></i>
                            @elseif(str_contains($roleSlug, 'kepeg'))
                                <i
                                    class="
                                        bi
                                        bi-person-vcard-fill
                                    "></i>
                            @elseif($roleSlug === 'pegawai')
                                <i
                                    class="
                                        bi
                                        bi-person-fill
                                    "></i>
                            @else
                                <i
                                    class="
                                        bi
                                        bi-person-badge-fill
                                    "></i>
                            @endif

                        </div>



                        <div class="
                                role-option-text
                            ">

                            <strong>

                                {{ $roleNama }}

                            </strong>


                            <span>

                                {{ $roleDeskripsi }}

                            </span>

                        </div>



                        @if ($isActive)
                            <span
                                class="
                                    role-active
                                ">

                                AKTIF

                            </span>
                        @else
                            <i class="
                                    bi
                                    bi-chevron-right
                                "
                                style="
                                    color:#aeb5bf;
                                    font-size:10px;
                                "></i>
                        @endif


                    </button>


                </form>


            @empty


                <div
                    style="
                        padding:30px 10px;
                        text-align:center;
                        color:#969eaa;
                        font-size:10px;
                    ">

                    <i class="
                            bi
                            bi-person-x
                        "
                        style="
                            display:block;
                            font-size:27px;
                            margin-bottom:9px;
                        "></i>


                    Belum ada role
                    untuk akun ini.

                </div>
            @endforelse


        </div>



        {{-- =================================================
             FOOTER
        ================================================== --}}

        <div class="role-modal-footer">


            <form method="POST" action="{{ route('samperin.logout') }}">

                @csrf


                <button type="submit" class="role-logout">

                    <i
                        class="
                            bi
                            bi-box-arrow-right
                        "></i>

                    &nbsp;

                    Keluar dari SAMPERIN

                </button>

            </form>


        </div>


    </div>

</div>



<script>
    function openRoleModal() {

        const modal =
            document.getElementById(
                'roleModal'
            );


        if (!modal) {

            return;

        }


        modal.classList.add(
            'show'
        );


        document.body.style.overflow =
            'hidden';

    }



    function closeRoleModal() {

        const modal =
            document.getElementById(
                'roleModal'
            );


        if (!modal) {

            return;

        }


        modal.classList.remove(
            'show'
        );


        document.body.style.overflow =
            '';

    }



    function closeRoleModalOutside(
        event
    ) {

        const modal =
            document.getElementById(
                'roleModal'
            );


        if (
            event.target === modal
        ) {

            closeRoleModal();

        }

    }



    document.addEventListener(
        'keydown',
        function(event) {

            if (
                event.key === 'Escape'
            ) {

                closeRoleModal();

            }

        }
    );
</script>
