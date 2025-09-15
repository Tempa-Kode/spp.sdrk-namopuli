<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kuitansi SPP - {{ $transaksiPertama->tagihan->siswa->nama_siswa }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #ddd;
        }

        /* Header Section */
        .header {
            background: #E07B39;
            color: white;
            padding: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            border: none;
        }

        .header-left {
            width: 70%;
        }

        .header-right {
            width: 30%;
            text-align: right;
        }

        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: white;
        }

        .school-subtitle {
            font-size: 14px;
            margin-bottom: 3px;
            color: rgba(255, 255, 255, 0.9);
        }

        .invoice-date {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.8);
        }

        .logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            text-align: center;
            line-height: 60px;
            margin: 0 auto 10px;
        }

        .invoice-number {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Invoice Info Bar */
        .invoice-info {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
        }

        .invoice-info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-info-table td {
            vertical-align: middle;
            border: none;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #E07B39;
        }

        .status-badge {
            background: #27ae60;
            color: white;
            padding: 6px 15px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        /* Main Content */
        .content {
            padding: 20px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .content-table td {
            vertical-align: top;
            padding: 10px;
            border: none;
        }

        .student-info {
            width: 80%;
            background: #f8f9fa;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }

        .amount-section {
            width: 80%;
            background: #E07B39;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-left: 10px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #E07B39;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .student-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .student-detail {
            font-size: 12px;
            color: #666;
            margin-bottom: 3px;
        }

        .amount-label {
            font-size: 12px;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.9);
        }

        .amount-value {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            color: white;
        }

        .amount-currency {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Amount in Words */
        .amount-words {
            background: #f7ddcb;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
            border: 1px solid #c99877;
            border-radius: 5px;
        }

        .amount-words-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .amount-words-value {
            font-size: 14px;
            font-weight: bold;
            color: #E07B39;
            font-style: italic;
        }

        /* Details Table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
            margin-top: 20px;
        }

        .details-table th {
            background: #E07B39;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
            border: 1px solid #E07B39;
        }

        .details-table td {
            padding: 10px 12px;
            border: 1px solid #dee2e6;
            font-size: 12px;
        }

        .details-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            width: 40%;
        }

        .detail-value {
            color: #333;
        }

        .status-lunas {
            color: #27ae60;
            font-weight: bold;
        }

        /* Payment Details Table */
        .payment-details-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
            margin-top: 20px;
        }

        .payment-details-table th {
            background: #E07B39;
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            border: 1px solid #E07B39;
        }

        .payment-details-table td {
            padding: 10px 12px;
            border: 1px solid #dee2e6;
            font-size: 12px;
            text-align: center;
        }

        .payment-details-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .payment-details-table .text-right {
            text-align: right;
        }

        .payment-details-table tfoot td {
            background: #f8f9fa;
            font-weight: bold;
            border-top: 2px solid #E07B39;
        }

        /* Footer */
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            margin-top: 30px;
        }

        .footer-text {
            color: #666;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .footer-contact {
            color: #E07B39;
            font-weight: bold;
            font-size: 11px;
        }

        /* Stamp Lunas - Watermark */
        .stamp-lunas {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            width: 400px;
            height: 400px;
            opacity: 0.15;
            z-index: 1;
            pointer-events: none;
        }

        .stamp-lunas img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .invoice-container {
            position: relative;
        }

        /* Print Styles */
        @media print {

            .btn,
            .alert,
            .card-header .btn {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .stamp-lunas {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }

        /* Non-print elements */
        .print-controls {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        @media print {
            .print-controls {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Print Controls -->
    <div class="print-controls">
        <button onclick="window.print()"
            style="background: #E07B39; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-right: 10px; cursor: pointer;">
            <i class="fa fa-print"></i> Cetak Kuitansi
        </button>
        <button onclick="window.history.back()"
            style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            Kembali
        </button>
    </div>

    <div class="invoice-container">
        <!-- Stamp Lunas -->
        <div class="stamp-lunas">
            @php
                $imagePath = public_path("assets/img/lunas.png");
                $imageData = "";
                if (file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $src = "data:image/png;base64," . $imageData;
                } else {
                    $src = "";
                }
            @endphp
            @if ($src)
                <img src="{{ $src }}" alt="LUNAS" />
            @endif
        </div>

        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <div class="school-name">SD RK NAMOPULI</div>
                        <div class="school-subtitle">Kuitansi Pembayaran</div>
                        <div class="invoice-date">Pembayaran {{ count($detailTagihan) }} Bulan</div>
                    </td>
                    <td class="header-right">
                        <div class="logo">
                            <img src="landing/img/logo.png" alt="" width="80">
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Invoice Info Bar -->
        <div class="invoice-info">
            <table class="invoice-info-table">
                <tr>
                    <td>
                        <div class="invoice-title">SPP BULANAN</div>
                    </td>
                    <td style="text-align: right;">
                        <span class="status-badge">LUNAS</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Student Info and Amount -->
            <table class="content-table">
                <tr>
                    <td>
                        <div class="student-info">
                            <div class="section-title">Data Siswa</div>
                            <div class="student-name">{{ $transaksiPertama->tagihan->siswa->nama_siswa }}</div>
                            <div class="student-detail"><strong>NISN:</strong>
                                {{ $transaksiPertama->tagihan->siswa->nisn }}</div>
                            <div class="student-detail"><strong>Kelas:</strong>
                                {{ $transaksiPertama->tagihan->siswa->kelas->tingkat_kelas ?? "-" }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="amount-section">
                            <div class="amount-label">Total Pembayaran</div>
                            <div class="amount-value">Rp {{ number_format($totalPembayaran, 0, ",", ".") }}</div>
                            <div class="amount-currency">IDR</div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Amount in Words -->
            <div class="amount-words">
                <div class="amount-words-label">Terbilang</div>
                <div class="amount-words-value">{{ \App\Helpers\Terbilang::convert($totalPembayaran) }} Rupiah</div>
            </div>

            <!-- Receipt Details -->
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Keterangan</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="detail-label">No. Kuitansi</td>
                        <td class="detail-value">{{ $transaksiPertama->kd_transaksi }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Tanggal Pembayaran</td>
                        <td class="detail-value">
                            {{ $transaksiPertama->tanggal_bayar instanceof \Carbon\Carbon
                                ? $transaksiPertama->tanggal_bayar->locale("id")->translatedFormat("l, d F Y H:i:s")
                                : \Carbon\Carbon::parse($transaksiPertama->tanggal_bayar)->locale("id")->translatedFormat("l, d F Y H:i:s") }}
                        </td>
                    </tr>
                    <tr>
                        <td class="detail-label">Jenis Pembayaran</td>
                        <td class="detail-value">SPP (Sumbangan Pembinaan Pendidikan) - {{ count($detailTagihan) }}
                            Bulan</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Status Pembayaran</td>
                        <td class="detail-value">
                            <span class="status-lunas">LUNAS</span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Payment Details Table -->
            <table class="payment-details-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Periode</th>
                        <th>Kode Tagihan</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailTagihan as $index => $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::createFromFormat("Y-m", $detail["bulan"])->locale("id")->translatedFormat("F Y") }}
                            </td>
                            <td>{{ $detail["kode_tagihan"] }}</td>
                            <td class="text-right">Rp {{ number_format($detail["nominal"], 0, ",", ".") }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold;">Total Pembayaran:</td>
                        <td class="text-right" style="font-weight: bold;">Rp
                            {{ number_format($totalPembayaran, 0, ",", ".") }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">Terima kasih atas pembayaran yang tepat waktu</div>
            <div class="footer-text">Kuitansi ini adalah bukti pembayaran yang sah</div>
            <div class="footer-text">Pembayaran untuk {{ count($detailTagihan) }} bulan</div>
            <div class="footer-contact">SD RK Namopuli | Dusun I Namopuli Desa SUMBUL KEC. STM HILIR KAB. DELI SERDANG
                SUMATERA UTARA</div>
        </div>
    </div>
</body>

</html>
