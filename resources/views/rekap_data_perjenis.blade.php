<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Rekapitulasi Data Pelanggaran</title>
</head>

<body>
    @foreach ($head as $h)
        <table width="100%" style="margin-bottom: 5px; border: 1px solid black;">
            <tr>
                <td rowspan="5" width="10%">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="width: 80px; height: 85px;">
                </td>
                <td style="text-transform: uppercase; font-size: 18px; font-weight: bold" width="90%">
                    KEMENTERIAN PERHUBUNGAN
                </td>
            </tr>
            <tr>
                <td style="text-transform: uppercase; font-size: 18px; font-weight: bold">
                    DIREKTORAT JENDERAL PERHUBUNGAN DARAT
                </td>
            </tr>
            <tr>
                <td style="text-transform: uppercase; font-size: 18px; font-weight: bold">{{ $h->bptd }}
                </td>
            </tr>
            <tr>
                <td style="text-transform: uppercase; font-size: 16px; font-weight: bold">Satuan Pelayanan
                    {{ $h->nama }}</td>
            </tr>
            <tr>
                <td style="font-size: 16px">{{ $h->alamat_uppkb }}</td>
            </tr>
        </table>
    @endforeach

    <table width="100%" style="margin-bottom: 5px; border: 1px solid black;">
        <tr>
            <td colspan="2" style="text-transform: uppercase; font-size: 16px; font-weight: bold; text-align: center"
                width="100%">
                {{ $judul }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-transform: uppercase; font-size: 14px; font-weight: bold; padding-left: 5px" width="50%">
                Interval : {{ $interval }}
            </td>
            <td style="text-transform: uppercase; font-size: 14px; font-weight: bold; padding-right: 5px; text-align: right"
                width="50%">
                {{ $tgl }}
            </td>
        </tr>
    </table>
    <table width="100%" style="font-size: 12px; border: 1px solid black;" border="1" cellpadding="0"
        cellspacing="0">
        <tr>
            <th width="10%">Waktu</th>
            <th>Total Pelanggaran</th>
            <th>Mobil Barang Bak Terbuka</th>
            <th>Mobil Barang Bak Tertutup</th>
            <th>Kereta Tempelan Bak Terbuka</th>
            <th>Mobil Penarik</th>
            <th>Mobil Tangki</th>
            <th>Kereta Tempelan</th>
            <th>Kendaraan Khusus</th>
            <th>Kereta Gandeng Bak Terbuka</th>
            <th>Kereta Gandeng Bak Tertutup</th>
            <th>Kendaraan Bermotor Roda Tiga</th>
        </tr>
        {{-- {{ dd($jk) }} --}}
        {{-- @foreach ($jk as $jk)
                <tr style="text-align: center">
                    <td>{{ $jk->waktu }}</td>
                    <td>{{ $jk->jml_pelanggaran }}</td>
                    <td>{{ $jk->mobil_barang_bak_terbuka }}</td>
                    <td>{{ $jk->mobil_barang_bak_tertutup }}</td>
                    <td>{{ $jk->kereta_tempelan_bak_terbuka }}</td>
                    <td>{{ $jk->mobil_penarik }}</td>
                    <td>{{ $jk->mobil_tangki }}</td>
                    <td>{{ $jk->kereta_tempelan }}</td>
                    <td>{{ $jk->kendaraan_khusus }}</td>
                    <td>{{ $jk->kereta_gandeng_bak_tertutup }}</td>
                    <td>{{ $jk->kereta_gandeng_bak_terbuka }}</td>
                    <td>{{ $jk->kendaraan_bermotor_roda_tiga }}</td>
                </tr>
            @endforeach --}}
        <?php
            $jml_pelanggaran = 0;
            $mobil_barang_bak_terbuka = 0;
            $mobil_barang_bak_tertutup = 0;
            $kereta_tempelan_bak_terbuka = 0;
            $mobil_penarik = 0;
            $mobil_tangki = 0;
            $kereta_tempelan = 0;
            $kendaraan_khusus = 0;
            $kereta_gandeng_bak_tertutup = 0;
            $kereta_gandeng_bak_terbuka = 0;
            $kendaraan_bermotor_roda_tiga = 0;
            foreach ($jk as $jk) { ?>
        <tr style="text-align: center; page-break-after: auto">
            <td>{{ $jk->waktu }}</td>
            <td>{{ $jk->jml_pelanggaran }}</td>
            <td>{{ $jk->mobil_barang_bak_terbuka }}</td>
            <td>{{ $jk->mobil_barang_bak_tertutup }}</td>
            <td>{{ $jk->kereta_tempelan_bak_terbuka }}</td>
            <td>{{ $jk->mobil_penarik }}</td>
            <td>{{ $jk->mobil_tangki }}</td>
            <td>{{ $jk->kereta_tempelan }}</td>
            <td>{{ $jk->kendaraan_khusus }}</td>
            <td>{{ $jk->kereta_gandeng_bak_tertutup }}</td>
            <td>{{ $jk->kereta_gandeng_bak_terbuka }}</td>
            <td>{{ $jk->kendaraan_bermotor_roda_tiga }}</td>
        </tr>
        <?php
                $jml_pelanggaran += $jk->jml_pelanggaran;
                $mobil_barang_bak_terbuka += $jk->mobil_barang_bak_terbuka;
                $mobil_barang_bak_tertutup += $jk->mobil_barang_bak_tertutup;
                $kereta_tempelan_bak_terbuka += $jk->kereta_tempelan_bak_terbuka;
                $mobil_penarik += $jk->mobil_penarik;
                $mobil_tangki += $jk->mobil_tangki;
                $kereta_tempelan += $jk->kereta_tempelan;
                $kendaraan_khusus += $jk->kendaraan_khusus;
                $kereta_gandeng_bak_tertutup += $jk->kereta_gandeng_bak_tertutup;
                $kereta_gandeng_bak_terbuka += $jk->kereta_gandeng_bak_terbuka;
                $kendaraan_bermotor_roda_tiga += $jk->kendaraan_bermotor_roda_tiga;
            } ?>
        <tr style="text-align: center; font-weight: bold">
            <td>TOTAL</td>
            <td>{{ $jml_pelanggaran }}</td>
            <td>{{ $mobil_barang_bak_terbuka }}</td>
            <td>{{ $mobil_barang_bak_tertutup }}</td>
            <td>{{ $kereta_tempelan_bak_terbuka }}</td>
            <td>{{ $mobil_penarik }}</td>
            <td>{{ $mobil_tangki }}</td>
            <td>{{ $kereta_tempelan }}</td>
            <td>{{ $kendaraan_khusus }}</td>
            <td>{{ $kereta_gandeng_bak_tertutup }}</td>
            <td>{{ $kereta_gandeng_bak_terbuka }}</td>
            <td>{{ $kendaraan_bermotor_roda_tiga }}</td>
        </tr>
    </table>

</body>


</html>
