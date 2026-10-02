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
            <th>Daya Angkut</th>
            <th>Dimensi</th>
            <th>Persyaratan Teknis</th>
            <th>Dokumen</th>
            <th>Tata Cara Muat</th>
            <th>Kelas Jalan</th>
            <th>Rambu Lalu Lintas</th>
        </tr>
        {{-- {{ dd($jp) }} --}}
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
            $daya_angkut = 0;
            $dimensi = 0;
            $persyaratan_teknis = 0;
            $dokumen = 0;
            $tata_cara_muat = 0;
            $kelas_jalan = 0;
            $rambu_lalu_lintas = 0;
            foreach ($jp as $jp) { ?>
        <tr style="text-align: center; page-break-after: auto">
            <td>{{ $jp->waktu }}</td>
            <td>{{ $jp->daya_angkut }}</td>
            <td>{{ $jp->dimensi }}</td>
            <td>{{ $jp->persyaratan_teknis }}</td>
            <td>{{ $jp->dokumen }}</td>
            <td>{{ $jp->tata_cara_muat }}</td>
            <td>{{ $jp->kelas_jalan }}</td>
            <td>{{ $jp->rambu_lalu_lintas }}</td>
        </tr>
        <?php
                $daya_angkut += $jp->daya_angkut;
                $dimensi += $jp->dimensi;
                $persyaratan_teknis += $jp->persyaratan_teknis;
                $dokumen += $jp->dokumen;
                $tata_cara_muat += $jp->tata_cara_muat;
                $kelas_jalan += $jp->kelas_jalan;
                $rambu_lalu_lintas += $jp->rambu_lalu_lintas;
            } ?>
        <tr style="text-align: center; font-weight: bold">
            <td>TOTAL</td>
            <td>{{ $daya_angkut }}</td>
            <td>{{ $dimensi }}</td>
            <td>{{ $persyaratan_teknis }}</td>
            <td>{{ $dokumen }}</td>
            <td>{{ $tata_cara_muat }}</td>
            <td>{{ $kelas_jalan }}</td>
            <td>{{ $rambu_lalu_lintas }}</td>
        </tr>
    </table>

</body>


</html>
