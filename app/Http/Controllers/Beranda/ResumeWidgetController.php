<?php

namespace App\Http\Controllers\Beranda;

use App\Http\Resources\ResumeWidgetResource as Resource;
use App\Http\Controllers\Controller;
use App\Models\Archive;
use App\Models\Capture;
use App\Models\Pelanggaran;
use App\Models\Petugas;
use App\Models\ResumeWidget as Model;
use App\Models\VerifikasiArchive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class ResumeWidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resume(Request $request)
    {
        $sort = 'created_at';
        $sortby = 'asc';
        $tahun = date('Y');
        $tahunLalu = date('Y', strtotime("-1 year"));
        $bulan = date('m');
        $hari = date('Y-m-d');
        // dd($hari);
        $bulanLalu = date("m", strtotime("-1 month"));
        $id = $request->id ?? null;
        $petugas_id = $request->petugas_id >> null;
        $role_id = $request->role_id ?? null;


        $query = Pelanggaran::where('is_active', true)
            ->whereYear('tgl_pelanggaran', $tahun)
            ->orderBy('tgl_pelanggaran', $sortby);


        $capturenow = Capture::where('is_active', true)->where('is_verifikasi', false)->whereDate('tgl_capture', $hari)->count('id');
        // $capture = Capture::where('is_active', true)->where('is_verifikasi', false)->count();
        $archive = VerifikasiArchive::where('is_active', true)->whereMonth('tgl_capture', $bulan)->count('id');
        $archivenow = VerifikasiArchive::where('is_active', true)->whereDate('tgl_capture', $hari)->count('id');
        $archive2now = Archive::where('is_active', true)->whereDate('tgl_capture', $hari)->count('id');
        $pelanggarannow = Pelanggaran::where('is_active', true)->whereDate('tgl_pelanggaran', $hari)->count('id');

        $thisMonth = $query->whereMonth('tgl_pelanggaran', $bulan)->count('id');
        $plg = $query->whereDate('tgl_pelanggaran', $hari)->count('id');

        if ($bulan === "01") {
            $lastMonth = Pelanggaran::where('is_active', true)
                ->whereYear('tgl_pelanggaran', $tahunLalu)
                ->whereMonth('tgl_pelanggaran', $bulanLalu)
                ->count('id');
        } else {
            $lastMonth = Pelanggaran::where('is_active', true)
                ->whereYear('tgl_pelanggaran', $tahun)
                ->whereMonth('tgl_pelanggaran', $bulanLalu)
                ->count('id');
        }

        if ($petugas_id) {
            $petugas = DB::table('jt_petugas')->where('id', $petugas_id)->first();
            $peruser = Pelanggaran::where('is_active', true)->whereDate('tgl_pelanggaran', $hari)->where('petugas_id', $petugas_id)->count('id');
        } else {
            $petugas = null;
            $peruser = Pelanggaran::where('is_active', true)->whereDate('tgl_pelanggaran', $hari)->where('created_at', $id)->count('id');
        }

        $role = DB::table('roles')->where('id', $role_id)->first();

        $lhr = Capture::where('is_active', true)->where('device_id', 2)->whereDate('tgl_capture', $hari)->count('id');
        // $wim = Capture::where('is_active', true)->where('device_id', 1)->whereDate('tgl_capture', $hari)->count('id');
        $wim = DB::table('jt_log_wim')->whereDate('tgl_penimbangan', $hari)->count('id');
        $sudahVerif = $archivenow + $archive2now + $pelanggarannow;

        $data = [
            'totalCapture' => $capturenow,
            'totalSudahVerif' => $sudahVerif,
            'totalPelanggaranTahunIni' => $query->count('id'),
            'totalPelanggaranBulanIni' => $thisMonth,
            'totalPelanggaranBulanLalu' => $lastMonth,
            'totalPelanggaranHariIni' => $plg,
            'totalArchiveBulanIni' => $archive,
            'totalArchiveHariIni' => $archivenow,
            'totalDeteksiLhr' => $lhr,
            'totalDeteksiWim' => $wim,
            'totalDataUser' => $peruser,
            'petugas' => $petugas,
            'role' => $role,
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $data);
    }

    public function getperbulan()
    {
        $tahun = date('Y');

        $bulanArr = [];
        $jml_verif = [];
        $jml_archive = [];
        $totalPerBulan = [];
        $bulan = [];

        for ($i = 1; $i < 13; $i++) {
            $bulan[] = Carbon::parse($tahun . '-' . $i)->isoFormat('MMMM');

            // $plg = Pelanggaran::where('is_active', true)
            //     ->whereMonth('tgl_pelanggaran', date('m', strtotime($i)))
            //     ->count();

            $bulanArr[] = '0';
            $totalPerBulan[] = 0;
            $jml_verif[] = 0;
            $jml_archive[] = 0;
        };

        $lap = DB::table('vr_total_pelanggaran_perbulan')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(jml_verifikasi) AS jml_verifikasi,
                sum(jml_archive) AS jml_archive,
                sum(jml_pelanggaran) AS total
            ")
            ->whereYear('waktu', $tahun)
            ->groupBy('bulan', 'tahun')
            ->orderBy('bulan', 'asc')
            ->get();

        foreach ($lap as $d) {
            $b = (int) $d->bulan - 1;
            $totalPerBulan[$b] = (int)$d->total;
            $jml_verif[$b] = (int)$d->jml_verifikasi;
            $jml_archive[$b] = (int)$d->jml_archive;
        }

        $data = [
            'bulan' => $bulan,
            'dataPerBulan' => $lap,
            'chartPerBulan' => [
                'label' => $bulan,
                'verifikasi' => $jml_verif,
                'archive' => $jml_archive,
                'total' => $totalPerBulan,
            ],
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $data);
    }

    public function getperjam()
    {

        $tanggal = date('Y-m-d');

        $total_verif_jam = [];
        $total_archive_jam = [];
        $jamArr = [];
        $totalPerJam = [];

        $db = DB::table('vr_total_pelanggaran_perjam')
            ->whereDate('waktu', $tanggal)
            ->orderBy('jam', 'asc')
            ->get();

        for ($i = 0; $i < 24; $i++) {
            $jamArr[] = $i . ':00';
            $total_verif_jam[] = 0;
        };

        foreach ($db as $d) {
            $b = (int) $d->jam;
            $jamArr[$b] = $d->jam . ':00';
            //$bulanArr[$b] = Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
            $total_verif_jam[$b] = (int)$d->jml_verifikasi;
        }

        $data = [
            'dataPerJam' => $db,
            'chartPerJam' => [
                'label' => $jamArr,
                'verifikasi' => $total_verif_jam,
            ],
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $data);
    }

    public function getperjeniskendaraan()
    {
        $tanggal = date('Y-m-d');
        $tahun = date('Y');
        $bulan = date('m');

        // $jenis_kendaraan = [];
        // $total = [];
        // $totalAll = 0;

        // $jk = DB::table('vr_pelanggaran')->selectRaw("jenis_kendaraan_id, jenis_kendaraan, count(*) as total")
        //     ->where('is_active', true)->whereMonth('tgl_pelanggaran', $bulan)
        //     ->groupBy('jenis_kendaraan_id', 'jenis_kendaraan')
        //     ->orderBy('total', 'desc')
        //     ->get();

        // foreach ($jk as $d) {
        //     $jenis_kendaraan[] = $d->jenis_kendaraan;
        //     $total[] = $d->total;
        //     $totalAll = $totalAll + $d->total;
        // }

        // $data = [
        //     'dataPerJenisKendaraan' => $jk,
        //     'chartPerJenisKendaraan' => [
        //         'label' => $jenis_kendaraan,
        //         'data' => $total
        //     ],
        //     'totalAll' => $totalAll
        // ];

        $query = DB::table('vr_total_pelanggaran_perjenis_kendaraan')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(jml_pelanggaran) as jml_pelanggaran,
                sum(kereta_tempelan_bak_terbuka) AS kereta_tempelan_bak_terbuka,
                sum(mobil_barang_bak_terbuka) AS mobil_barang_bak_terbuka,
                sum(mobil_barang_bak_tertutup) AS mobil_barang_bak_tertutup,
                sum(mobil_penarik) AS mobil_penarik,
                sum(mobil_tangki) AS mobil_tangki,
                sum(kereta_tempelan) AS kereta_tempelan,
                sum(kendaraan_khusus) AS kendaraan_khusus,
                sum(kereta_gandeng_bak_tertutup) AS kereta_gandeng_bak_tertutup,
                sum(kereta_gandeng_bak_terbuka) AS kereta_gandeng_bak_terbuka,
                sum(kendaraan_bermotor_roda_tiga) AS kendaraan_bermotor_roda_tiga
            ")
            ->whereDate('waktu', $tanggal)
            // ->whereMonth('waktu', $bulan)
            ->groupBy('bulan', 'tahun')
            ->orderBy('bulan', 'asc')
            ->get();

        return new Resource(true, __('message.GET_BERHASIL'), $query);
    }

    public function getperkategorikepemilikan()
    {
        $tanggal = date('Y-m-d');
        $tahun = date('Y');
        $bulan = date('m');

        $label = [
            'PERSEORANGAN',
            'PERUSAHAAN',
        ];

        $db = DB::table('vr_total_pelanggaran_perkategori')->selectRaw("
                sum(perusahaan) AS perusahaan,
                sum(perseorangan) AS perseorangan
            ")
            ->whereDate('waktu', $tanggal)
            // ->whereMonth('waktu', $bulan)
            ->first();

        $total = [
            $db->perseorangan,
            $db->perusahaan,
        ];
        $totalAll = $db->perseorangan + $db->perusahaan;

        $data = [
            'dataPerKategoriKepemilikan' => $db,
            'chartPerKategoriKepemilikan' => [
                'label' => $label,
                'data' => $total
            ],
            'totalAll' => $totalAll
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $data);
    }

    public function getperberat()
    {
        $tanggal = date('Y-m-d');
        $tahun = date('Y');
        $bulan = date('m');

        $label = [
            '5 - 20%',
            '21 - 40%',
            '41 - 60%',
            '61 - 80%',
            '81 - 100%',
            '>100%',
        ];

        $db = DB::table('vr_lap_pelanggaran_range_berat')->selectRaw("
                sum(range_5_20) AS range_5_20,
                sum(range_21_40) AS range_21_40,
                sum(range_41_60) AS range_41_60,
                sum(range_61_80) AS range_61_80,
                sum(range_81_100) AS range_81_100,
                sum(range_up_100) AS range_up_100
            ")
            ->whereDate('waktu', $tanggal)
            // ->whereYear('waktu', $tahun)
            // ->whereMonth('waktu', $bulan)
            ->first();

        $berat = [
            $db->range_5_20,
            $db->range_21_40,
            $db->range_41_60,
            $db->range_61_80,
            $db->range_81_100,
            $db->range_up_100,
        ];

        $data = [
            'dataPerRangeBerat' => $db,
            'chartPerRangeBerat' => [
                'label' => $label,
                'data' => $berat
            ]
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $data);
    }

    public function dd()
    {
        $sort = 'created_at';
        $sortby = 'asc';
        $tanggal = date('Y-m-d');
        $tahun = Carbon::now()->isoFormat('YYYY');

        $query = Pelanggaran::where('is_active', true)->whereYear('tgl_pelanggaran', $tahun)->orderBy($sort, $sortby)->get();
        $capture = Capture::where('is_active', true)->where('is_verifikasi', false)->orderBy($sort, $sortby)->get();
        $bulanArr = [];
        $jml_verif = [];
        $total_verif_jam = [];
        $jml_archive = [];
        $total_archive_jam = [];
        $totalPerBulan = [];
        $totalBulanIni = [];
        $totalBulanLalu = [];
        $bulanIni = Carbon::now()->isoFormat('MMMM');
        $bulanLalu = date("m", strtotime("-1 month"));
        $jamArr = [];
        $totalPerJam = [];

        foreach ($query as $d) {

            if (Carbon::parse($d->tgl_pelanggaran)->isoFormat('MMMM') === $bulanIni) {
                array_push($totalBulanIni, $d);
            };
            if (date("m", strtotime($d->tgl_pelanggaran)) === $bulanLalu) {
                array_push($totalBulanLalu, $d);
            };
        }

        // $dt = Pelanggaran::selectRaw("EXTRACT(YEAR FROM tgl_pelanggaran) AS tahun, EXTRACT(MONTH from tgl_pelanggaran) as bulan, COUNT(*) FILTER (WHERE device_id = 1) as wim, COUNT(*) FILTER (WHERE device_id = 2) as lhr, count(*) as total")
        //     ->where('is_active', true)->whereYear('tgl_pelanggaran', $tahun)
        //     ->groupBy('bulan', 'tahun')
        //     ->orderBy('bulan', 'asc')
        //     ->get();
        // //DB::select("SELECT EXTRACT(YEAR FROM tgl_pelanggaran) AS tahun, EXTRACT(MONTH FROM tgl_pelanggaran) AS bulan, COUNT(*) as ttl FROM vr_pelanggaran WHERE is_active = TRUE AND is_archive = FALSE AND EXTRACT(YEAR FROM tgl_pelanggaran) = '$tahun' GROUP BY 1,bulan ORDER BY bulan ASC");
        // foreach ($dt as $c) {
        //     $bulanArr[] = Carbon::parse($c->tahun . '-' . $c->bulan)->isoFormat('MMMM');
        //     $totalPerBulan[] = $c->total;
        //     $jml_verif[] = $c->jml_verifikasi;
        //     $jml_archive[] = $c->jml_archive;
        // }

        $db = DB::table('vr_total_pelanggaran_perjam')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jam', 'asc')
            ->get();

        foreach ($db as $b) {
            $jamArr[] = $b->jam . ':00';
            $totalPerJam[] = $b->jml_pelanggaran;
            $total_verif_jam[] = $b->jml_verif;
            $total_archive_jam[] = $b->jml_archive;
        }

        $jk = Pelanggaran::selectRaw("jenis_kendaraan_id, jenis_kendaraan, count(*) as total")
            ->where('is_active', true)->whereYear('tgl_pelanggaran', $tahun)
            ->groupBy('jenis_kendaraan_id', 'jenis_kendaraan')
            ->orderBy('jenis_kendaraan_id', 'asc')
            ->get();

        $lap = DB::table('vr_total_pelanggaran_perbulan')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(jml_verifikasi) AS jml_verifikasi,
                sum(jml_archive) AS jml_archive,
                sum(jml_pelanggaran) AS total
            ")
            ->whereYear('waktu', $tahun)
            ->groupBy('bulan', 'tahun')
            ->orderBy('bulan', 'asc')
            ->get();

        foreach ($lap as $c) {
            $bulanArr[] = Carbon::parse($c->tahun . '-' . $c->bulan)->isoFormat('MMMM');
            $totalPerBulan[] = $c->total;
            $jml_verif[] = $c->jml_verifikasi;
            $jml_archive[] = $c->jml_archive;
        }

        $data = [
            // 'dataCapture' => $capture,
            // 'dataPelanggaran' => $query,
            'totalPelanggaranAll' => count($query),
            'totalCapture' => count($capture),
            'totalPelanggaranNow' => count($totalBulanIni),
            'totalPelanggaranLastNow' => count($totalBulanLalu),
            'dataPerJam' => $db,
            'dataPerBulan' => $lap,
            'chartPerJam' => [
                'label' => $jamArr,
                'verifikasi' => $total_verif_jam,
                'archive' => $total_archive_jam,
                'data' => $totalPerJam,
            ],
            'chartPerBulan' => [
                'label' => $bulanArr,
                'verifikasi' => $jml_verif,
                'archive' => $jml_archive,
                'total' => $totalPerBulan,
            ],
            'chartPerJenisKendaraan' => $jk,
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $data);
    }
}
