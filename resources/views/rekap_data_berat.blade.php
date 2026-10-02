<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <th>5 s/d 20</th>
            <th>21 s/d 40</th>
            <th>41 s/d 60</th>
            <th>61 s/d 80</th>
            <th>81 s/d 100</th>
            <th>>100</th>
        </tr>
        {{-- {{ dd($jk) }} --}}
        {{-- @foreach ($km as $km)
                @php
                    $range_5_20 = 0;
                    $range_21_40 = 0;
                    $range_41_60 = 0;
                    $range_61_80 = 0;
                    $range_81_100 = 0;
                    $range_up_100 = 0;
                @endphp
                <tr style="text-align: center">
                    <td>{{ $km->waktu }}</td>
                    <td>{{ $km->range_5_20 }}</td>
                    <td>{{ $km->range_21_40 }}</td>
                    <td>{{ $km->range_41_60 }}</td>
                    <td>{{ $km->range_61_80 }}</td>
                    <td>{{ $km->range_81_100 }}</td>
                    <td>{{ $km->range_up_100 }}</td>
                </tr>
                @php
                    $range_5_20 += $km->range_5_20;
                    $range_21_40 += $km->range_21_40;
                    $range_41_60 += $km->range_41_60;
                    $range_61_80 += $km->range_61_80;
                    $range_81_100 += $km->range_81_100;
                    $range_up_100 += $km->range_up_100;
                @endphp
            @endforeach --}}

        <?php
            $range_5_20 = 0;
            $range_21_40 = 0;
            $range_41_60 = 0;
            $range_61_80 = 0;
            $range_81_100 = 0;
            $range_up_100 = 0;
            foreach ($km as $km) {
            ?>
        <tr style="text-align: center">
            <td>{{ $km->waktu }}</td>
            <td>{{ $km->range_5_20 }}</td>
            <td>{{ $km->range_21_40 }}</td>
            <td>{{ $km->range_41_60 }}</td>
            <td>{{ $km->range_61_80 }}</td>
            <td>{{ $km->range_81_100 }}</td>
            <td>{{ $km->range_up_100 }}</td>
        </tr>
        <?php
            $range_5_20 += $km->range_5_20;
            $range_21_40 += $km->range_21_40;
            $range_41_60 += $km->range_41_60;
            $range_61_80 += $km->range_61_80;
            $range_81_100 += $km->range_81_100;
            $range_up_100 += $km->range_up_100;
            } ?>

        <tr style="text-align: center; font-weight: bold">
            <td>TOTAL</td>
            <td>{{ $range_5_20 }}</td>
            <td>{{ $range_21_40 }}</td>
            <td>{{ $range_41_60 }}</td>
            <td>{{ $range_61_80 }}</td>
            <td>{{ $range_81_100 }}</td>
            <td>{{ $range_up_100 }}</td>
        </tr>
    </table>

</body>


</html>
