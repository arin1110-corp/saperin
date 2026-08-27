@extends('dashboard-admin.layouts.app')

@section('title', 'Data Pegawai')

@section('breadcrumb', 'Kepegawaian')

@section('header-title', 'Data Pegawai')


@section('page-style')

    <style>
        .pegawai-heading {

            margin-bottom: 23px;

        }


        .pegawai-heading h1 {

            margin: 0;

            font-size: 30px;

            font-weight: 750;

            letter-spacing: -.8px;

            color: #172238;

        }


        .pegawai-heading p {

            margin: 7px 0 0;

            font-size: 11px;

            color: #959daa;

        }


        .pegawai-card {

            background: #fff;

            border:
                1px solid #e7eaf0;

            border-radius: 14px;

            overflow: hidden;

            box-shadow:
                0 5px 15px rgba(20, 30, 50, .025);

        }


        .pegawai-toolbar {

            padding:
                19px 20px;

            border-bottom:
                1px solid #eef0f3;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

        }


        .pegawai-count-title {

            font-size: 14px;

            font-weight: 750;

        }


        .pegawai-count {

            margin-top: 4px;

            font-size: 10px;

            color: #979eaa;

        }


        .pegawai-search {

            position: relative;

            width: 320px;

        }


        .pegawai-search i {

            position: absolute;

            left: 12px;

            top: 50%;

            transform:
                translateY(-50%);

            color: #a2a9b4;

            font-size: 13px;

        }


        .pegawai-search input {

            width: 100%;

            padding:
                11px 12px 11px 36px;

            border:
                1px solid #e1e5eb;

            border-radius: 8px;

            outline: none;

            font-size: 11px;

            color: #29344a;

        }


        .pegawai-search input:focus {

            border-color: #d58a4d;

            box-shadow:
                0 0 0 3px rgba(213, 138, 77, .08);

        }


        .pegawai-table-wrapper {

            overflow-x: auto;

        }


        .pegawai-table {

            width: 100%;

            border-collapse: collapse;

            min-width: 750px;

        }


        .pegawai-table th {

            padding:
                13px 18px;

            background: #fafbfc;

            border-bottom:
                1px solid #edf0f3;

            text-align: left;

            color: #8f97a5;

            font-size: 9px;

            text-transform: uppercase;

            letter-spacing: .5px;

        }


        .pegawai-table td {

            padding:
                15px 18px;

            border-bottom:
                1px solid #f0f2f5;

            color: #4d5769;

            font-size: 13px;

        }


        .pegawai-table tbody tr:hover {

            background: #fffaf6;

        }


        .pegawai-name {

            color: #273147;

            font-size: 13px;

            font-weight: 700;

        }


        .pegawai-status {

            display: inline-flex;

            padding:
                6px 9px;

            border-radius: 6px;

            font-size: 8px;

            font-weight: 700;

        }


        .pegawai-status-active {

            background: #eaf7ef;

            color: #318957;

        }


        .pegawai-status-inactive {

            background: #fff0f0;

            color: #d45a5a;

        }


        .pegawai-empty {

            text-align: center;

            padding:
                48px 20px !important;

            color: #9aa2ae !important;

            font-size: 10px !important;

        }


        .pegawai-empty i {

            display: block;

            font-size: 29px;

            margin-bottom: 9px;

        }


        @media (max-width: 650px) {

            .pegawai-toolbar {

                flex-direction: column;

                align-items: stretch;

            }

            .pegawai-search {

                width: 100%;

            }

        }
    </style>

@endsection


@section('content')

    <div class="admin-content">


        <div class="pegawai-heading">

            <h1>

                Data Pegawai

            </h1>


            <p>

                Kelola data pegawai SAMPERIN.

            </p>

        </div>



        <div class="pegawai-card">


            <div class="pegawai-toolbar">


                <div>

                    <div class="pegawai-count-title">

                        Daftar Pegawai

                    </div>


                    <div class="pegawai-count">

                        {{ number_format($totalPegawai ?? 0, 0, ',', '.') }}

                        pegawai

                    </div>

                </div>


                <div class="pegawai-search">

                    <i class="bi bi-search"></i>

                    <input type="text" id="pegawaiSearch" placeholder="Cari nama atau NIP...">

                </div>


            </div>



            <div class="pegawai-table-wrapper">


                <table class="pegawai-table">


                    <thead>

                        <tr>

                            <th>
                                Nama Pegawai
                            </th>

                            <th>
                                NIP
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody id="pegawaiTableBody">


                        @forelse($pegawaiTerbaru ?? []
                            as $pegawai)
                            <tr>


                                <td>

                                    <div class="pegawai-name">

                                        {{ $pegawai->user_nama ?? '-' }}

                                    </div>

                                </td>


                                <td>

                                    {{ $pegawai->user_nip ?? '-' }}

                                </td>


                                <td>

                                    @if ((int) ($pegawai->user_status ?? 0) === 1)
                                        <span
                                            class="
                                            pegawai-status
                                            pegawai-status-active
                                        ">

                                            Aktif

                                        </span>
                                    @else
                                        <span
                                            class="
                                            pegawai-status
                                            pegawai-status-inactive
                                        ">

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                            </tr>


                        @empty


                            <tr>

                                <td colspan="3" class="pegawai-empty">

                                    <i class="bi bi-people"></i>

                                    Belum ada data pegawai.

                                </td>

                            </tr>
                        @endforelse


                    </tbody>


                </table>


            </div>


        </div>


    </div>


    <script>
        const pegawaiSearch =
            document.getElementById(
                'pegawaiSearch'
            );


        if (pegawaiSearch) {

            pegawaiSearch.addEventListener(
                'keyup',
                function() {

                    const keyword =
                        this.value
                        .toLowerCase();


                    const rows =
                        document.querySelectorAll(
                            '#pegawaiTableBody tr'
                        );


                    rows.forEach(
                        function(row) {

                            const text =
                                row.textContent
                                .toLowerCase();


                            row.style.display =
                                text.includes(keyword) ?
                                '' :
                                'none';

                        }
                    );

                }
            );

        }
    </script>

@endsection
