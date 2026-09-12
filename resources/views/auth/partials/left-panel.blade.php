<section
    class="
        building-panel
        relative
        flex
        w-full
        flex-col
        overflow-hidden
        p-8
        text-white
        sm:p-10
        lg:w-[52%]
        lg:p-12
        xl:p-14
    ">

    <div class="bali-pattern"></div>


    <div class="left-content flex h-full flex-col">

        {{-- BRAND --}}

        @include('auth.partials.brand')


        <div class="gold-line"></div>


        {{-- TITLE --}}

        <div class="mt-7">

            <h1
                class="
                    max-w-[560px]
                    text-[30px]
                    font-black
                    leading-[1.15]
                    tracking-tight
                    text-white
                    sm:text-[36px]
                    lg:text-[40px]
                ">

                {!! $leftTitle ?? 'Dinas Kebudayaan<br>Provinsi Bali' !!}

            </h1>


            <p
                class="
                    mt-5
                    max-w-[550px]
                    text-[15px]
                    leading-7
                    text-white/90
                    sm:text-[16px]
                ">

                {{ $leftDescription ??
                    'Sistem terintegrasi untuk mengelola data pegawai, administrasi, dan berkas internal Dinas Kebudayaan Provinsi Bali secara terstruktur dan mudah.' }}

            </p>

        </div>


        {{-- FEATURES --}}

        <div
            class="
                feature-box
                mt-8
                w-full
                max-w-[430px]
                rounded-2xl
                px-5
                py-2
                sm:px-6
                lg:mt-auto
            ">

            @php

                $features = $leftFeatures ?? [
                    [
                        'title' => 'Manajemen Pegawai',
                        'description' => 'Kelola data dan informasi pegawai secara terstruktur',
                        'icon' => 'user',
                    ],

                    [
                        'title' => 'Berkas Internal',
                        'description' => 'Kelola dan akses berkas pegawai secara terpusat',
                        'icon' => 'folder',
                    ],

                    [
                        'title' => 'Administrasi',
                        'description' => 'Mendukung pengelolaan administrasi internal',
                        'icon' => 'document',
                    ],

                    [
                        'title' => 'Terstruktur',
                        'description' => 'Data dan berkas tersimpan dengan rapi dan mudah diakses',
                        'icon' => 'database',
                    ],
                ];

            @endphp


            @foreach ($features as $feature)
                <div
                    class="
                        feature-item
                        flex
                        items-center
                        gap-4
                        py-4
                    ">

                    <div
                        class="
                            flex
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            text-[#ffbd50]
                        ">

                        @if ($feature['icon'] === 'user')
                            <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="9" cy="8" r="3" stroke-width="1.7" />

                                <circle cx="17" cy="10" r="2.5" stroke-width="1.7" />

                                <path stroke-linecap="round" stroke-width="1.7" d="
                                        M3 20
                                        c0-3.2 2.7-5 6-5
                                        s6 1.8 6 5

                                        M15 15
                                        c3 0 5 1.6 5 4
                                    " />
                            </svg>
                        @elseif($feature['icon'] === 'folder')
                            <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="
                                        M4 6
                                        a2 2 0 012-2
                                        h5
                                        l2 2
                                        h5
                                        a2 2 0 012 2
                                        v10
                                        a2 2 0 01-2 2
                                        H6
                                        a2 2 0 01-2-2V6z
                                    " />

                                <path stroke-linecap="round" stroke-width="1.7" d="
                                        M8 11h8
                                        M8 15h5
                                    " />
                            </svg>
                        @elseif($feature['icon'] === 'document')
                            <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="6" y="4" width="12" height="17" rx="2" stroke-width="1.7" />

                                <path stroke-linecap="round" stroke-width="1.7" d="
                                        M9 8h6
                                        M9 12h6
                                        M9 16h4
                                    " />
                            </svg>
                        @else
                            <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <ellipse cx="12" cy="6" rx="7" ry="3"
                                    stroke-width="1.7" />

                                <path stroke-linecap="round" stroke-width="1.7" d="
                                        M5 6v6
                                        c0 1.7 3.1 3 7 3
                                        s7-1.3 7-3V6

                                        M5 12v6
                                        c0 1.7 3.1 3 7 3
                                        s7-1.3 7-3v-6
                                    " />
                            </svg>
                        @endif

                    </div>


                    <div>

                        <div
                            class="
                                text-[16px]
                                font-bold
                                text-white
                            ">
                            {{ $feature['title'] }}
                        </div>

                        <div
                            class="
                                mt-1
                                text-[13px]
                                leading-5
                                text-white/70
                            ">
                            {{ $feature['description'] }}
                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

</section>
