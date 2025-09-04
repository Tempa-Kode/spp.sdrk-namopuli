<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Tagihan SPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #666;
        }

        .header p {
            margin: 0;
            color: #888;
            font-size: 10px;
        }

        .filter-info {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .filter-info h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
        }

        .filter-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .filter-label {
            font-weight: bold;
            color: #555;
        }

        .statistics {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            color: #333;
        }

        td {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            .footer {
                position: fixed;
                bottom: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN TAGIHAN SPP</h1>
        <h2>SD RK NAMOPULI</h2>
        <p>Jl. Alamat Sekolah, Kota, Provinsi | Telp: (021) 123-4567</p>
    </div>

    <!-- Filter Information -->
    <div class="filter-info">
        <h3>Filter Laporan</h3>
        <div class="filter-row">
            <span class="filter-label">Kelas:</span>
            <span>{{ $filters["kelas"] }}</span>
        </div>
        <div class="filter-row">
            <span class="filter-label">Bulan:</span>
            <span>{{ $filters["bulan"] }}</span>
        </div>
        <div class="filter-row">
            <span class="filter-label">Tahun:</span>
            <span>{{ $filters["tahun"] }}</span>
        </div>
        <div class="filter-row">
            <span class="filter-label">Status:</span>
            <span>{{ $filters["status"] }}</span>
        </div>
        <div class="filter-row">
            <span class="filter-label">Tanggal Cetak:</span>
            <span>{{ date("d F Y H:i:s") }}</span>
        </div>
    </div>

    <!-- Data Table -->
    @if ($tagihan->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="20%">Nama Siswa</th>
                    <th width="15%">NISN</th>
                    <th width="10%">Kelas</th>
                    <th width="12%">Bulan</th>
                    <th width="18%" class="text-right">Nominal</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="10%">Tgl Update</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tagihan as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->siswa->nama_siswa ?? "-" }}</td>
                        <td>{{ $item->siswa->nisn ?? "-" }}</td>
                        <td>Kelas {{ $item->siswa->kelas->tingkat_kelas ?? "-" }}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat("Y-m", $item->bulan)->format("M Y") }}</td>
                        <td class="text-right">Rp {{ number_format($item->tarif->nominal ?? 0, 0, ",", ".") }}</td>
                        <td class="text-center">
                            @if ($item->status == "lunas")
                                <span class="badge badge-success">Lunas</span>
                            @else
                                <span class="badge badge-warning">Belum Bayar</span>
                            @endif
                        </td>
                        <td>{{ $item->updated_at->format("d/m/Y") }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div style="margin-top: 30px; border-top: 2px solid #333; padding-top: 15px;">
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <strong>Ringkasan:</strong><br>
                    Total {{ number_format($stats["total_tagihan"]) }} tagihan dengan nominal Rp
                    {{ number_format($stats["total_nominal"], 0, ",", ".") }}<br>
                    {{ number_format($stats["tagihan_lunas"]) }} tagihan lunas ({{ $stats["persentase_lunas"] }}%) dan
                    {{ number_format($stats["tagihan_belum_bayar"]) }} belum bayar
                </div>
                <div style="text-align: right;">
                    <strong>Mengetahui,</strong><br><br><br>
                    <u>Kepala Sekolah</u><br>
                    NIP. 123456789012345678
                </div>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 50px; color: #666;">
            <h3>Tidak ada data yang sesuai dengan filter yang dipilih</h3>
            <p>Silakan ubah kriteria filter untuk melihat data tagihan SPP.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh Sistem Informasi SPP SD RK NAMOPULI pada
            {{ date("d F Y H:i:s") }}</p>
    </div>
</body>

</html>
