<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bukti Pelanggaran</title>
</head>

<body>
    @foreach ($pelanggaran as $d)
        <div style="display: flex; flex-wrap: wrap;">

            @foreach ($head as $h)
                <table width="100%" style="margin-bottom: -5px; line-height: 0.8">
                    <tr>
                        <td rowspan="5" width="13%">
                            <img src="{{ $logo }}" alt="Logo" style="width: 60px; height: 65px;">
                        </td>
                        <td style="text-transform: uppercase; font-size: 16px; font-weight: bold" width="74%">
                            KEMENTERIAN PERHUBUNGAN
                        </td>
                        <td rowspan="5" width=13% align="center">
                            <img src="{{ $qrurl }}" alt="QR" style="width: 60px; height: 60px;">
                            <div style="font-size: 12px; font-weight: bold">
                                Ref :
                            </div>
                            <div style="text-transform: uppercase; font-size: 12px; font-weight: bold;">
                                {{ $d->no_ref }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-transform: uppercase; font-size: 16px; font-weight: bold; margin-top: -10px;">
                            DIREKTORAT JENDERAL PERHUBUNGAN DARAT
                        </td>
                    </tr>
                    <tr>
                        <td style="text-transform: uppercase; font-size: 16px; font-weight: bold; margin-top: -10px;">
                            {{ $h->bptd }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-transform: uppercase; font-size: 14px; font-weight: bold; margin-top: -10px;">
                            Satuan Pelayanan
                            {{ $h->nama }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px">{{ $h->alamat_uppkb }}</td>
                    </tr>
                </table>
            @endforeach

            <hr style="color: black">

            <table width="100%" style="margin-bottom: 10px; margin-top: 10px">
                <tr>
                    <td align="center">
                        <div
                            style="text-decoration: underline; text-transform: uppercase; font-size: 16px; font-weight: bold">
                            {{ $title }}
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%" style="margin-bottom: 15px; font-size: 14px;">
                <tr>
                    <td width="15%">
                        Tanggal
                    </td>
                    <td width="2%">
                        :
                    </td>
                    <td width="40%">
                        {{ $tgl }}
                    </td>
                    <td width="6%" rowspan="5"></td>
                    <td width="15%">
                        Jam
                    </td>
                    <td width="2%">
                        :
                    </td>
                    <td width="20%">
                        {{ $jam }}
                    </td>
                </tr>
                <tr>
                    <td>
                        No Kendaraan
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $d->no_kendaraan }}
                    </td>
                    <td>
                        No Uji
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $d->no_uji }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Pemilik
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $d->nama_pemilik }}
                    </td>
                    <td>
                        JBI
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $d->jbi_uji }} Kg
                    </td>
                </tr>
                <tr>
                    <td>
                        Alamat
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $d->alamat_pemilik }}
                    </td>
                    <td>
                        Berat Timbang
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $d->berat_timbang }} Kg
                    </td>
                </tr>
                <tr>
                    <td>
                        Masa Berlaku
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $masa_berlaku }}
                    </td>
                    <td>
                        Berat Lebih
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $d->kelebihan_berat }} Kg
                    </td>
                </tr>

            </table>

            <table width="100%" style="margin-bottom: 5px">

                <tr>
                    <td align="left" width="30%">
                        <div style="text-transform: uppercase; font-size: 14px; font-weight: bold">Photo plat kendaraan
                            :</div>
                    </td>
                    @foreach ($plat as $plat)
                        <td align="left" width="20%">
                            <div style="width: 100%; height: auto;">
                                <img src="{{ $plat->img_url }}" alt="Image" style="width: 100%; height: 50px;">
                            </div>

                        </td>
                    @endforeach
                    @if ($total < 2)
                        <td width="50%"></td>
                    @else
                        <td width="30%"></td>
                    @endif
                </tr>

            </table>

            <table width="100%">

                <tr>
                    @foreach ($detailcapture as $item)
                        @if (count($detailcapture) > 1)
                            <td align="center" width="50%">
                                <div style="width: 100%; height: 200px;">
                                    <img src="{{ $item->img_url }}" alt="Image" style="width: 100%; height: 200px;">
                                </div>

                            </td>
                        @else
                            <td width="25%"></td>
                            <td align="center">
                                <div style="width: 100%; height: auto;">
                                    <img src="{{ $item->img_url }}" alt="Image" style="width: 100%;">
                                </div>

                            </td>
                            <td width="25%"></td>
                        @endif
                    @endforeach
                </tr>

            </table>

            <table width="100%" style="margin-top: -2px">

                <tr>
                    @foreach ($detailcapture as $item)
                        @if (count($detailcapture) > 1)
                            <td align="left">
                                <div style="font-size: 14px; margin-left: 8px">
                                    {{ date('d-m-Y', strtotime($item->tgl_capture)) }}
                                </div>

                            </td>
                            <td align="right">
                                <div style="font-size: 14px; margin-right: 8px">
                                    {{ date('H:i:s', strtotime($item->tgl_capture)) }}
                                </div>

                            </td>
                        @else
                            <td width="25%"></td>
                            <td align="left">
                                <div style="font-size: 14px; margin-left: 8px">
                                    {{ date('d-m-Y', strtotime($item->tgl_capture)) }}
                                </div>

                            </td>
                            <td align="right">
                                <div style="font-size: 14px; margin-right: 8px">
                                    {{ date('H:i:s', strtotime($item->tgl_capture)) }}
                                </div>

                            </td>
                            <td width="25%"></td>
                        @endif
                        {{-- @if ($loop->index % 2 == 0)
                        <tr>
                    @endif
                    <td align="center">
                        <div style="width: 100%; height: auto;">
                            <img src="{{ public_path('images/' . $item->img_name) }}" alt="Image"
                                style="width: 100%;">
                        </div>
                    </td>
                    @if ($loop->index % 2 == 1)
                        </tr>
                    @endif --}}
                    @endforeach
                </tr>

            </table>

            <table width="100%" style="margin-top: 15px;">
                <tr>
                    <td align="center">
                        <div style="text-transform: uppercase; font-size: 14px; font-weight: bold">Pelanggaran :</div>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <div style="font-size: 14px; font-weight: bold;">{{ $detail_pelanggaran }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <div style="text-transform: uppercase; font-size: 14px; margin-top: 5px; font-weight: bold">
                            Pasal :
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <div style="font-size: 14px;">{{ $pasal }}</div>
                    </td>
                </tr>
            </table>


            {{-- <div style="text-align: center; margin-top: 15px; margin-bottom: 20px">

                <div style="text-transform: uppercase; font-size: 14px; font-weight: bold">Pelanggaran :</div>
                <div style="font-size: 14px; font-weight: bold; margin-top: 5px">{{ $detail_pelanggaran }}</div>
                <div style="text-transform: uppercase; font-size: 14px; margin-top: 15px; font-weight: bold">Pasal :
                </div>
                <div style="font-size: 14px; margin-top:5px">{{ $pasal }}</div>
            </div> --}}

            <table width="100%" style="margin-top: 15px">
                <tr>
                    <td align="center" width="45%">
                        <div style="text-transform: uppercase; font-size: 14px; font-weight: bold">
                            Operator
                        </div>
                    </td>
                    <td width="10%">

                    </td>
                    <td align="center" width="45%">
                        <div style="text-transform: uppercase; font-size: 14px; font-weight: bold">
                            PPNS
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="margin-top: 60px"></div>
                    </td>
                </tr>
                <tr>
                    <td align="center" width="45%">
                        <div
                            style="text-decoration: underline; text-transform: uppercase; font-size: 14px; font-weight: bold">
                            ( {{ $operator }} )
                        </div>
                    </td>
                    <td width="10%">

                    </td>
                    <td align="center" width="45%">
                        <div
                            style="text-decoration: underline; text-transform: uppercase; font-size: 14px; font-weight: bold">
                            ( {{ $ppns }} )
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" width="45%">

                    </td>
                    <td width="10%">

                    </td>
                    <td align="center" width="45%">
                        <div style="text-transform: uppercase; font-size: 14px; font-weight: bold">
                            NIP. {{ $nipppns }}
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%" style="margin-top: 5px; margin-bottom: -5px">
                <tr>
                    <td align="center">
                        <div style=" font-size: 14px;">
                            Mengetahui,
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%">
                <tr>
                    <td align="center">
                        <div style="text-transform: uppercase; font-size: 14px; font-weight: bold">
                            Korsatpel
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="margin-top: 60px"></div>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <div
                            style="text-decoration: underline; text-transform: uppercase; font-size: 14px; font-weight: bold">
                            ( {{ $korsatpel }} )
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <div style="text-transform: uppercase; font-size: 14px; font-weight: bold">
                            NIP. {{ $nip }}
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    @endforeach

</body>

</html>
