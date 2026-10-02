<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bukti Pelanggaran</title>
</head>

<body>

    <table width="100%" style="margin-bottom: 5px; border: 1px solid black;">
        <tr>
            <td width="8%">
                <img src="{{ $logo }}" alt="Logo" style="width: 80px; height: 85px;">
            </td>
            <td style="text-transform: uppercase; font-size: 20px; font-weight: bold; padding-left: 5px" width="90%">
                KEMENTERIAN PERHUBUNGAN <br>
                DIREKTORAT JENDERAL PERHUBUNGAN DARAT
            </td>
        </tr>
    </table>

    <table width="100%" style="margin-bottom: 5px; border: 1px solid black;">
        <tr>
            <td style="text-transform: uppercase; font-size: 16px; font-weight: bold; padding-left: 5px" width="13%">
                UPPKB
            </td>
            <td style="text-transform: uppercase; font-size: 16px; font-weight: bold" width="2%">
                :
            </td>
            <td style="text-transform: uppercase; font-size: 16px; font-weight: bold" width="65%">
                {{ $uppkb }}
            </td>
            <td rowspan="4" width="20%" style="text-align: right; padding-right: 5px">
                <img src="{{ $qrurl }}" alt="Logo" style="width: 80px; height: 85px;">
            </td>
        </tr>
        <tr>
            <td style="text-transform: uppercase; font-size: 16px; font-weight: bold; padding-left: 5px" width="13%">
                tanggal
            </td>
            <td style="text-transform: uppercase; font-size: 16px; font-weight: bold" width="2%">
                :
            </td>
            @if ($tgl_dari === $tgl_sampai)
                <td style="font-size: 16px; font-weight: bold" width="65%">
                    {{ $tgl_dari }}
                </td>
            @else
                <td style="font-size: 16px; font-weight: bold" width="65%">
                    {{ $tgl_dari }} s/d {{ $tgl_sampai }}
                </td>
            @endif
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>

    <table width="100%" style="font-size: 12px; border: 1px solid black; margin-bottom: 5px;" border="1"
        cellpadding="0" cellspacing="0">
        <thead style="text-transform: uppercase">
            <th>No</th>
            <th>Waktu</th>
            <th>Device</th>
            <th>No Kendaraan</th>
            <th>No Uji</th>
            <th>Masa Berlaku</th>
            <th>Jenis Kendaraan</th>
            <th>Sumbu</th>
            <th>JBI</th>
            <th>Hasil Timbang</th>
            <th>Berat lebih</th>
            <th>Persen Lebih</th>
            <th>Pelanggaran</th>
            <th>Pasal</th>
            <th>Operator</th>
        </thead>
        <tbody>
            @foreach ($pelanggaran as $index => $d)
                @php
                    
                    if (empty($d->petugas)) {
                        $petugas = $d->createdBy->nama_lengkap;
                    } else {
                        $petugas = $d->petugas->nama;
                    }
                    
                    $pasal = '';
                    $detailpelanggaran = '';
                    $datapasal = $d->detailpasal;
                    for ($i = 0; $i < count($datapasal); $i++) {
                        if ($i == count($datapasal) - 1) {
                            $pasal .= $datapasal[$i]->desk_pasal;
                        } else {
                            $pasal .= $datapasal[$i]->desk_pasal . ', ';
                        }
                    }
                    $dp = $d->detailpelanggaran;
                    for ($i = 0; $i < count($dp); $i++) {
                        if ($i == count($dp) - 1) {
                            $detailpelanggaran .= $dp[$i]->deskripsi;
                        } else {
                            $detailpelanggaran .= $dp[$i]->deskripsi . ', ';
                        }
                    }
                @endphp
                <tr style="text-align: center">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($d->tgl_pelanggaran)) }}</td>
                    <td>{{ $d->device->nama }}</td>
                    <td>{{ $d->no_kendaraan }}</td>
                    <td>{{ $d->no_uji }}</td>
                    <td>{{ date('d-m-Y', strtotime($d->tgl_masa_berlaku)) }}</td>
                    <td>{{ $d->jenis_kendaraan }}</td>
                    <td>{{ $d->sumbu }}</td>
                    <td>{{ $d->jbi_uji }} Kg</td>
                    <td>{{ $d->berat_timbang }} Kg</td>
                    <td>{{ $d->kelebihan_berat }} Kg</td>
                    <td>{{ $d->prosen_lebih }} %</td>
                    <td>{{ $detailpelanggaran }}</td>
                    <td>{{ $pasal }}</td>
                    <td>{{ $petugas }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>


</html>
