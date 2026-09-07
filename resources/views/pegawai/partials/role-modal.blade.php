<div class="modal fade" id="modalPilihRole" tabindex="-1" aria-labelledby="modalPilihRoleLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered samperin-role-modal-dialog">

        <div class="modal-content samperin-role-modal">

            {{-- HEADER --}}
            <div class="modal-header samperin-role-modal-header">

                <div>
                    <div class="samperin-role-modal-title" id="modalPilihRoleLabel">
                        Pilih Role
                    </div>

                    <div class="samperin-role-modal-subtitle">
                        Pilih role yang ingin Anda gunakan.
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup">
                </button>

            </div>

            {{-- BODY --}}
            <div class="modal-body samperin-role-modal-body">

                @php
                    $roleList = collect($user?->roles ?? []);

                    if ($roleList->isEmpty() && $user && method_exists($user, 'roles')) {
                        $roleList = $user->roles()->where('role_status', true)->orderBy('role_nama')->get();
                    }

                    $activeRoleUid = session('samperin_role_uid');
                @endphp

                @forelse ($roleList as $role)
                    @php
                        $isActive = strtolower((string) $role->role_uid) === strtolower((string) $activeRoleUid);

                        $roleSlug = strtolower(trim((string) $role->role_slug));

                        $roleIcon = match ($roleSlug) {
                            'administrator' => 'bi-shield-lock',
                            'kepegawaian' => 'bi-briefcase',
                            'pegawai' => 'bi-person',
                            default => 'bi-person-circle',
                        };
                    @endphp

                    <form method="POST" action="{{ route('samperin.role.switch') }}" class="samperin-role-form">

                        @csrf

                        <input type="hidden" name="role_uid" value="{{ $role->role_uid }}">

                        <button type="submit" class="samperin-role-option {{ $isActive ? 'active' : '' }}"
                            {{ $isActive ? 'disabled' : '' }}>

                            <div class="samperin-role-option-icon">
                                <i class="bi {{ $roleIcon }}"></i>
                            </div>

                            <div class="samperin-role-option-content">

                                <div class="samperin-role-option-name">
                                    {{ $role->role_nama }}
                                </div>

                                @if ($isActive)
                                    <div class="samperin-role-option-status">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Role aktif
                                    </div>
                                @endif

                            </div>

                            @if ($isActive)
                                <div class="samperin-role-option-check">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                            @else
                                <div class="samperin-role-option-arrow">
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            @endif

                        </button>

                    </form>

                @empty

                    <div class="samperin-role-empty">
                        <div class="samperin-role-empty-icon">
                            <i class="bi bi-person-x"></i>
                        </div>

                        <div class="samperin-role-empty-title">
                            Role tidak tersedia
                        </div>

                        <div class="samperin-role-empty-text">
                            Tidak ada role aktif yang dapat digunakan.
                        </div>
                    </div>
                @endforelse

            </div>

        </div>

    </div>

</div>
{{-- =========================================================
     MODAL UPLOAD BERKAS PEGAWAI
========================================================= --}}

<div class="modal fade" id="samperinUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content samperin-upload-modal">

            <div class="modal-header">

                <div class="samperin-upload-header">

                    <div class="samperin-upload-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>

                    <div>
                        <h5>
                            Upload Berkas
                        </h5>

                        <p>
                            Lengkapi berkas yang diminta oleh kepegawaian.
                        </p>
                    </div>

                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

            </div>


            <form method="POST" id="samperinUploadForm" enctype="multipart/form-data">

                @csrf

                <div class="modal-body">

                    <div class="samperin-upload-request">

                        <div class="samperin-upload-request-label">
                            Permintaan
                        </div>

                        <div class="samperin-upload-request-title" id="samperinUploadJudul">
                            -
                        </div>

                        <div class="samperin-upload-request-deadline" id="samperinUploadDeadline">
                            -
                        </div>

                    </div>


                    <div class="samperin-upload-field">

                        <label for="samperinUploadFile">
                            Pilih Berkas
                            <span>*</span>
                        </label>

                        <div class="samperin-file-box">

                            <input type="file" name="file" id="samperinUploadFile" required>

                            <div class="samperin-file-icon">
                                <i class="bi bi-file-earmark-arrow-up"></i>
                            </div>

                            <div class="samperin-file-text">

                                <strong id="samperinFileName">
                                    Pilih file untuk diupload
                                </strong>

                                <small>
                                    Maksimal 50 MB
                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="samperin-upload-cancel" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="samperin-upload-submit">
                        <i class="bi bi-cloud-arrow-up"></i>
                        Upload Berkas
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
