<header
    class="
        admin-header

        flex
        items-center
        justify-between

        px-5

        md:px-7

        lg:px-8
    ">


    {{-- LEFT --}}

    <div class="flex items-center gap-4">

        <button type="button" id="adminMenuButton"
            class="
                flex
                h-10
                w-10

                items-center
                justify-center

                rounded-xl

                border
                border-slate-200

                bg-white

                text-slate-600

                lg:hidden
            ">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />

            </svg>

        </button>


        <div>

            <div
                class="
                    text-[12px]
                    font-semibold
                    text-slate-400
                ">
                SAMPERIN
            </div>


            <div
                class="
                    text-[15px]
                    font-black
                    text-slate-800
                ">
                Administrasi Sistem
            </div>

        </div>

    </div>


    {{-- RIGHT --}}

    <div class="
            flex
            items-center
            gap-3
        ">

        <div class="
                hidden
                text-right

                sm:block
            ">

            <div
                class="
                    text-[13px]
                    font-bold
                    text-slate-800
                ">
                {{ $user->user_nama ?? 'Administrator' }}
            </div>


            <div
                class="
                    text-[10px]
                    font-semibold
                    uppercase
                    tracking-wide
                    text-slate-400
                ">
                Administrator
            </div>

        </div>


        <div
            class="
                flex
                h-10
                w-10

                items-center
                justify-center

                rounded-full

                bg-orange-100

                text-sm
                font-black

                text-[#a84e15]
            ">

            {{ strtoupper(substr($user->user_nama ?? 'A', 0, 1)) }}

        </div>

    </div>

</header>
