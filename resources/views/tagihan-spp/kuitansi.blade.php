<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kuitansi Pembayaran SPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .kuitansi {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0;
            padding: 0;
        }

        .content {
            margin-bottom: 30px;
        }

        .details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .details td {
            padding: 8px;
            border: none;
        }

        .details td:first-child {
            width: 200px;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }

        .signature {
            margin-top: 80px;
        }

        .border-bottom {
            border-bottom: 1px solid #000;
        }
    </style>
</head>

<body>
    <div class="kuitansi">
        <div class="header">
            <h2>KUITANSI PEMBAYARAN SPP</h2>
            <h3>SD RK NAMOPULI</h3>
            <p>Jl. Pendidikan No. 123, Kota XYZ</p>
        </div>

        <div class="content">
            <table class="details">
                <tr>
                    <td>No. Kuitansi</td>
                    <td>: {{ $transaksi->kd_transaksi ?? "-" }}</td>
                </tr>
                <tr>
                    <td>Tanggal Pembayaran</td>
                    <td>: {{ $transaksi->tanggal_bayar ? date("d/m/Y", strtotime($transaksi->tanggal_bayar)) : "-" }}
                    </td>
                </tr>
                <tr>
                    <td>Telah terima dari</td>
                    <td>: {{ $tagihan->siswa->nama_siswa }}</td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>: {{ $tagihan->siswa->nisn }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>: {{ $tagihan->siswa->kelas->nama_kelas }}</td>
                </tr>
                <tr>
                    <td>Pembayaran</td>
                    <td>: SPP Bulan {{ $tagihan->bulan }}</td>
                </tr>
                <tr class="border-bottom">
                    <td>Jumlah</td>
                    <td>: Rp {{ number_format($transaksi->jumlah_bayar, 0, ",", ".") }}</td>
                </tr>
            </table>

            <p>Terbilang: <em>{{ ucwords(terbilang($transaksi->jumlah_bayar)) }} Rupiah</em></p>
        </div>

        <div class="footer">
            <p>{{ date("d F Y") }}</p>
            <div class="signature">
                <p>Petugas</p>
                <br><br><br>
                <p>{{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>
</body>

</html>
