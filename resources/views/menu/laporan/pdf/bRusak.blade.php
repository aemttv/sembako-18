<!DOCTYPE html>
<html lang="en">
@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Laporan Barang Rusak</title>
    <style>
        body {
            font-family: 'poppins', sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .row {
            display: flex;
            justify-content: space-between;
            /* Ensures items are on opposite sides */
            align-items: center;
            /* Vertically centers the text */
            margin-bottom: 10px;
            /* Adds space between rows */
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .row p {
            margin: 0;
            /* Removes default margin */
            font-size: 16px;
            font-weight: bold;
        }

        .info {
            font-size: 14px;
            /* Adjust font size if needed */
        }

        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 12px;
            background: #fff;
        }

        .pdf-table th,
        .pdf-table td {
            border: 1px solid #444;
            padding: 8px 6px;
            text-align: left;
        }

        .pdf-table th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .pdf-table thead tr:first-child th {
            background: #e0e7ef;
            font-size: 15px;
            letter-spacing: 1px;
            border-bottom: 2px solid #444;
        }

        .pdf-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .pdf-table tbody tr:hover {
            background: #e6f7ff;
        }

        .pdf-table .text-right {
            text-align: right;
        }

        .pdf-table .text-center {
            text-align: center;
        }
    </style>
</head>

<?php
?>

<body>
    <div class="container">
        <div class="header" style="text-align: center; margin-bottom: 16px;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/logo_1.jpg'))) }}"
                alt="Toko Sembako Logo" style="height: 65px; width: 200px; display: block; margin: 0 auto 10px auto;">
            <h1 style="margin: 0; font-size: 22px;">TOKO SEMBAKO 18</h1>
            <h1 style="margin: 0; font-size: 22px;">LAPORAN BARANG RUSAK</h1>
        </div>
        <div class="container">
            <table class="table" style="margin-bottom: 20px; margin-top: 20px">
                <tbody>
                    <tr>
                        <td class="text-right"><strong>Periode Laporan</strong>:&nbsp;
                            @if ($tglMasuk == $tglAkhir)
                                {{ \Carbon\Carbon::parse($tglMasuk)->translatedFormat('F Y') }}
                            @else
                                {{ \Carbon\Carbon::parse($tglMasuk)->translatedFormat('j F Y') }} -
                                {{ \Carbon\Carbon::parse($tglAkhir)->translatedFormat('j F Y') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Tanggal Pembuatan Laporan</strong>:&nbsp;
                            {{ \Carbon\Carbon::now()->translatedFormat('j F Y') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <table class="pdf-table">
            <thead>
                <tr>
                    <th colspan="7" class="text-center">
                        <strong>Barang Rusak Detail</strong>
                    </th>
                </tr>
                <tr>
                    <th>ID Barang Rusak</th>
                    <th>ID Akun</th>
                    <th>Barcode</th>
                    <th>Kuantitas</th>
                    <th>Keterangan</th>
                    <th>Tanggal Rusak</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($bRusak as $data)
                    @foreach ($data->detailRusak as $detail)
                        <tr>
                            {{-- <td class="px-4 py-2">{{ $no++ }}</td> --}}
                            <td>{{ $data->idBarangRusak }}</td>
                            <td>{{ $data->penanggungJawab }}</td>
                            <td>{{ $detail->barcode }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>{{ $detail->kategoriAlasan->alasan() }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->tglRusak)->translatedFormat('d F Y') }}</td>
                            <td>
                                @if ($data->statusRusak == 2)
                                    <span class="text-yellow-500 font-semibold">Pending</span>
                                @elseif ($data->statusRusak == 1)
                                    <span class="text-green-500 font-semibold">Approved</span>
                                @elseif ($data->statusRusak == 0)
                                    <span class="text-red-500 font-semibold">Rejected</span>
                                @else
                                    <span class="text-gray-500">Unknown</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <!-- Grand Total Row -->
                <tr>
                    <td colspan="7" style="text-align: center; font-weight: bold;">
                        <p><span style="font-weight: bold">Total Barang Rusak</span>
                            <br>
                            {{ $bRusak->sum(function ($data) {
                                return $data->detailRusak->count();
                            }) }}
                            Barang
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 24px;">
    <strong>Keterangan:</strong>
    {{-- Barcode List Table --}}
<table style="width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 12px;">
    <thead>
        <tr>
            <th style="border: 1px solid #444; padding: 4px 8px; background: #f2f2f2;">Barcode</th>
            <th style="border: 1px solid #444; padding: 4px 8px; background: #f2f2f2;">Nama Barang</th>
        </tr>
    </thead>
    <tbody>
        @foreach($BarcodeList as $barcode)
            <tr>
                <td style="border: 1px solid #444; padding: 4px 8px;">
                    {{ $barcode['barcode'] ?? '' }}
                </td>
                <td style="border: 1px solid #444; padding: 4px 8px;">
                    {{ $barcode['namaBarang'] ?? '' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Akun List Table --}}
<table style="width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 12px;">
    <thead>
        <tr>
            <th style="border: 1px solid #444; padding: 4px 8px; background: #f2f2f2;">ID Akun</th>
            <th style="border: 1px solid #444; padding: 4px 8px; background: #f2f2f2;">Nama Penanggung Jawab</th>
        </tr>
    </thead>
    <tbody>
        @foreach($AkunList as $akun)
            <tr>
                <td style="border: 1px solid #444; padding: 4px 8px;">
                    {{ $akun['idAkun'] ?? '' }}
                </td>
                <td style="border: 1px solid #444; padding: 4px 8px;">
                    {{ $akun['namaAkun'] ?? '' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
</body>

</html>
