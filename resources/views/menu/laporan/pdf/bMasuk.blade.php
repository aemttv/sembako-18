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
    <title>Laporan Barang Masuk</title>
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
            <h1 style="margin: 0; font-size: 22px;">LAPORAN BARANG MASUK</h1>
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
                    <th colspan="9" class="text-center">
                        <strong>Barang Masuk Detail</strong>
                    </th>
                </tr>
                <tr>
                    <th>No.</th>
                    <th>ID Barang</th>
                    <th>Supplier</th>
                    <th>Staff</th>
                    <th>Nama Barang</th>
                    <th>Kuantitas/Berat</th>
                    <th>Subtotal</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Kadaluarsa</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($bMasuk as $data)
                    @foreach ($data->detailMasuk as $detail)
                        <tr>
                            <td class="px-4 py-2 text-center">{{ $no++ }}</td>
                            <td class="px-4 py-2 text-center">{{ $detail->idBarang }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->supplier->nama }} ({{ $data->idSupplier }})</td>
                            <td class="px-4 py-2 text-center">{{ $data->akun->nama }} ({{ $data->idAkun }})</td>
                            <td class="px-4 py-2 text-left">{{ $detail->barangDetail->barang->namaBarang ?? 'Nama Barang' }}</td>
                            <td class="px-4 py-2 text-center">
                                {{ $detail->jumlahMasuk }}
                                @if ($detail->barangDetail->barang->satuan->namaSatuan() == 'pcs/eceran')
                                    pcs
                                @elseif($detail->barangDetail->barang->satuan->namaSatuan() == 'kg')
                                    kg
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right">Rp.{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center">
                                {{ \Carbon\Carbon::parse($data->tglMasuk)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ \Carbon\Carbon::parse($detail->tglKadaluarsa)->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <!-- Grand Total Row -->
                <tr>
                    <td colspan="6" style="text-align: right; font-weight: bold;">Grand Total</td>
                    <td style="font-weight: bold;">Rp.{{ number_format($grandTotal, 0, ',', '.') }}</td>
                    <td colspan="2" style="text-align: center; font-weight: bold;">
                        <p><span style="font-weight: bold">Total Barang Masuk</span>   
                            <br>
                            {{ $bMasuk->sum(function ($data) {
                                return $data->detailMasuk->count();
                            }) }}
                            Barang
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
</body>

</html>
