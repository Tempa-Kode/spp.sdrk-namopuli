<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Tagihan SPP Tahunan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
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
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .filter-info h3 {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #333;
        }

        .filter-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .filter-label {
            font-weight: bold;
            color: #555;
        }

        .statistics {
            margin-bottom: 25px;
        }

        .stats-summary {
            display: flex;
            justify-content: space-around;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        .monthly-stats {
            margin-top: 15px;
        }

        .monthly-stats h4 {
            margin-bottom: 10px;
            font-size: 14px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            color: #333;
        }

        td {
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
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

        .badge-info {
            background-color: #17a2b8;
        }

        .progress-bar {
            background-color: #e9ecef;
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
            display: inline-block;
            width: 80px;
        }

        .progress-fill {
            background-color: #28a745;
            height: 100%;
            border-radius: 5px;
        }

        .summary-section {
            border-top: 2px solid #333;
            padding-top: 15px;
            margin-top: 25px;
        }

        .summary-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .summary-table td {
            border: none;
            padding: 8px 12px;
            font-size: 11px;
        }

        .summary-table .label {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 30%;
        }

        .footer {
            position: fixed;
            bottom: 15px;
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

        .chart-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .chart-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .month-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        .month-item:last-child {
            border-bottom: none;
        }

        .month-name {
            font-weight: bold;
            width: 80px;
        }

        .month-stats {
            display: flex;
            gap: 15px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN TAGIHAN SPP TAHUNAN {{ $filters["tahun"] }}</h1>
        <h2>SD RK NAMOPULI</h2>
        <p>Dusun I Namopuli 	Desa	SUMBUL	KEC. STM HILIR	KAB. DELI SERDANG SUMATERA UTARA</p>
    </div>

    <!-- Filter Information -->
    <div class="filter-info">
        <h3>Informasi Laporan</h3>
        <div class="filter-row">
            <span class="filter-label">Kelas:</span>
            <span>{{ $filters["kelas"] }}</span>
        </div>
        <div class="filter-row">
            <span class="filter-label">Tahun:</span>
            <span>{{ $filters["tahun"] }}</span>
        </div>
        <div class="filter-row">
            <span class="filter-label">Tanggal Cetak:</span>
            <span>{{ date("d F Y H:i:s") }}</span>
        </div>
        <div class="filter-row">
            <span class="filter-label">Periode:</span>
            <span>Januari - Desember {{ $filters["tahun"] }}</span>
        </div>
    </div>

    <!-- Statistics Summary -->
    @if ($tagihan->count() > 0)
        <!-- Monthly Summary Table -->
        <table style="margin-top: 20px;">
            <thead>
                <tr>
                    <th width="15%" class="text-center">Bulan</th>
                    <th width="12%" class="text-center">Total Tagihan</th>
                    <th width="18%" class="text-right">Total Nominal</th>
                    <th width="12%" class="text-center">Lunas</th>
                    <th width="18%" class="text-right">Nominal Lunas</th>
                    <th width="12%" class="text-center">Belum Bayar</th>
                    <th width="13%" class="text-center">% Lunas</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalTagihanBulan = 0;
                    $totalNominalBulan = 0;
                    $totalLunasBulan = 0;
                    $totalNominalLunasBulan = 0;
                    $totalBelumBayarBulan = 0;
                @endphp
                @foreach($statistikBulanan as $bulan => $stat)
                @php
                    $totalTagihanBulan += $stat['total_tagihan'];
                    $totalNominalBulan += $stat['total_nominal'];
                    $totalLunasBulan += $stat['tagihan_lunas'];
                    $totalNominalLunasBulan += $stat['nominal_lunas'];
                    $totalBelumBayarBulan += $stat['tagihan_belum_bayar'];
                    $persentase = $stat['total_tagihan'] > 0 ? round(($stat['tagihan_lunas'] / $stat['total_tagihan']) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $stat['nama_bulan'] }}</td>
                    <td class="text-center">{{ number_format($stat['total_tagihan']) }}</td>
                    <td class="text-right">Rp {{ number_format($stat['total_nominal'], 0, ",", ".") }}</td>
                    <td class="text-center">{{ number_format($stat['tagihan_lunas']) }}</td>
                    <td class="text-right">Rp {{ number_format($stat['nominal_lunas'], 0, ",", ".") }}</td>
                    <td class="text-center">{{ number_format($stat['tagihan_belum_bayar']) }}</td>
                    <td class="text-center">{{ $persentase }}%</td>
                </tr>
                @endforeach
                <tr style="background-color: #e9ecef; font-weight: bold;">
                    <td class="text-center">TOTAL</td>
                    <td class="text-center">{{ number_format($totalTagihanBulan) }}</td>
                    <td class="text-right">Rp {{ number_format($totalNominalBulan, 0, ",", ".") }}</td>
                    <td class="text-center">{{ number_format($totalLunasBulan) }}</td>
                    <td class="text-right">Rp {{ number_format($totalNominalLunasBulan, 0, ",", ".") }}</td>
                    <td class="text-center">{{ number_format($totalBelumBayarBulan) }}</td>
                    <td class="text-center">{{ $totalTagihanBulan > 0 ? round(($totalLunasBulan / $totalTagihanBulan) * 100, 1) : 0 }}%</td>
                </tr>
            </tbody>
        </table>

        <div class="page-break"></div>

        <!-- Detail Data Table -->
        <h4 style="margin-bottom: 15px;">Detail Data Tagihan SPP Tahun {{ $filters["tahun"] }}</h4>
        <table>
            <thead>
                <tr>
                    <th width="4%" class="text-center">No</th>
                    <th width="18%">Nama Siswa</th>
                    <th width="13%">NISN</th>
                    <th width="8%">Kelas</th>
                    <th width="10%">Bulan</th>
                    <th width="15%" class="text-right">Nominal</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="12%">Tgl Update</th>
                    <th width="10%">Dibayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tagihan as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->siswa->nama_siswa ?? "-" }}</td>
                        <td>{{ $item->siswa->nisn ?? "-" }}</td>
                        <td>{{ $item->siswa->kelas->tingkat_kelas ?? "-" }}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat("Y-m", $item->bulan)->format("M") }}</td>
                        <td class="text-right">Rp {{ number_format($item->tarif->nominal ?? 0, 0, ",", ".") }}</td>
                        <td class="text-center">
                            @if ($item->status == "lunas")
                                <span class="badge badge-success">Lunas</span>
                            @else
                                <span class="badge badge-warning">Belum</span>
                            @endif
                        </td>
                        <td>{{ $item->updated_at->format("d/m/Y") }}</td>
                        <td>{{ $item->status == "lunas" ? $item->updated_at->format("d/m/Y") : "-" }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary-section">

            <div style="margin-top: 30px;">
                <p style="font-size: 10px; color: #666; text-align: justify;">
                    <strong>Catatan:</strong> Laporan ini menampilkan data tagihan SPP untuk tahun {{ $filters["tahun"] }}
                    dengan data per bulan. Data mencakup jumlah tagihan, nominal, status pembayaran, dan persentase
                    ketercapaian pembayaran.
                </p>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 50px; color: #666;">
            <h3>Tidak ad a data tagihan SPP untuk tahun {{ $filters["tahun"] }}</h3>
            <p>Silakan periksa data atau pilih tahun yang berb eda.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan Tagihan SPP Tahunan {{ $filters["tahun"] }} - SD RK NAMOPULI - Digenerate pada {{ date("d F Y H:i:s") }}</p>
    </div>
</body>

</html>
