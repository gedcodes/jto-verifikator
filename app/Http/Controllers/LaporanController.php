<?php

namespace App\Http\Controllers;

use App\Http\Resources\LaporanResource as Resource;
use App\Http\Controllers\Controller;
use App\Models\Archive;
use App\Models\Capture;
use App\Models\Pelanggaran;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jenis = $request->input('jenis_laporan');
        $interval = $request->input('interval');
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');
        $sort = 'created_at';
        $sortby = 'asc';

        $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        if ($jenis === 1 || $jenis === '1') {
            if ($interval === 1 || $interval === '1') {
                $query = DB::table('vr_total_pelanggaran_perbulan')
                    ->whereYear('waktu', $tahun)
                    ->whereMonth('waktu', $bulan)
                    ->get();
            } else {
                $query = DB::table('vr_total_pelanggaran_perbulan')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(jml_verifikasi) AS jml_verifikasi,
                sum(jml_archive) AS jml_archive,
                sum(jml_pelanggaran) AS jml_pelanggaran
            ")
                    ->whereYear('waktu', $tahun)
                    ->groupBy('bulan', 'tahun')
                    ->orderBy('bulan', 'asc')
                    ->get();
            }
        } elseif ($jenis === 2 || $jenis === '2') {
            if ($interval === 1 || $interval === '1') {
                $query = DB::table('vr_total_pelanggaran_perjenis_kendaraan')
                    ->whereYear('waktu', $tahun)
                    ->whereMonth('waktu', $bulan)
                    ->get();
            } else {
                $query = DB::table('vr_total_pelanggaran_perjenis_kendaraan')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(kereta_tempelan_bak_terbuka) AS kereta_tempelan_bak_terbuka,
                sum(mobil_barang_bak_terbuka) AS mobil_barang_bak_terbuka,
                sum(mobil_barang_bak_tertutup) AS mobil_barang_bak_tertutup,
                sum(mobil_penarik) AS mobil_penarik,
                sum(mobil_tangki) AS mobil_tangki,
                sum(kereta_tempelan) AS kereta_tempelan,
                sum(kendaraan_khusus) AS kendaraan_khusus,
                sum(kereta_gandeng_bak_tertutup) AS kereta_gandeng_bak_tertutup,
                sum(kereta_gandeng_bak_terbuka) AS kereta_gandeng_bak_terbuka,
                sum(kendaraan_bermotor_roda_tiga_angkutan_barang_bak_muatan_tertutu) AS kendaraan_bermotor_roda_tiga_angkutan_barang_bak_muatan_tertutu
            ")
                    ->whereYear('waktu', $tahun)
                    ->groupBy('bulan', 'tahun')
                    ->orderBy('bulan', 'asc')
                    ->get();
            }
        } elseif ($jenis === 3 || $jenis === '3') {
            if ($interval === 1 || $interval === '1') {
            } else {
            }
        } elseif ($jenis === 4 || $jenis === '4') {
            if ($interval === 1 || $interval === '1') {
            } else {
            }
        } else {
            if ($interval === 1 || $interval === '1') {
            } else {
            }
        }

        return new Resource(true, __('message.GET_BERHASIL'), $query);
    }

    public function pelanggaran(Request $request)
    {
        $interval = $request->input('interval');
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');

        if ($interval === 1 || $interval === '1') {
            $query = DB::table('vr_total_pelanggaran_perbulan')
                ->whereYear('waktu', $tahun)
                ->whereMonth('waktu', $bulan)
                ->get();
        } else {
            $query = DB::table('vr_total_pelanggaran_perbulan')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(jml_capture) AS jml_capture,
                sum(jml_verifikasi) AS jml_verifikasi,
                sum(jml_archive) AS jml_archive,
                sum(jml_pelanggaran) AS jml_pelanggaran
            ")
                ->whereYear('waktu', $tahun)
                ->groupBy('bulan', 'tahun')
                ->orderBy('bulan', 'asc')
                ->get();
        }

        return new Resource(true, __('message.GET_BERHASIL'), $query);
    }

    public function perjeniskendaraan(Request $request)
    {
        $interval = $request->input('interval');
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');

        if ($interval === 1 || $interval === '1') {
            $data = [];
            $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

            $query = DB::table('vr_total_pelanggaran_perjenis_kendaraan')
                ->whereYear('waktu', $tahun)
                ->whereMonth('waktu', $bulan)
                ->get();

            for ($i = 1; $i < $jmlHari + 1; $i++) {
                $data[] = [
                    'waktu' => date('Y-m-d', strtotime($tahun . '-' . $bulan . '-' . $i)),
                    'jml_pelanggaran' => '0',
                    'kereta_tempelan_bak_terbuka' => '0',
                    'mobil_barang_bak_terbuka' => '0',
                    'mobil_barang_bak_tertutup' => '0',
                    'mobil_penarik' => '0',
                    'mobil_tangki' => '0',
                    'kereta_tempelan' => '0',
                    'kendaraan_khusus' => '0',
                    'kereta_gandeng_bak_tertutup' => '0',
                    'kereta_gandeng_bak_terbuka' => '0',
                    'kendaraan_bermotor_roda_tiga' => '0'
                ];
            };
            foreach ($query as $d) {
                $b = (int) date('d', strtotime($d->waktu)) - 1;
                //$bulanArr[$b] = Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                $data[$b] = $d;
            }
        } else {
            $data = [];
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
                ->whereYear('waktu', $tahun)
                ->groupBy('bulan', 'tahun')
                ->orderBy('bulan', 'asc')
                ->get();

            for ($i = 1; $i < 13; $i++) {
                $data[] = [
                    'tahun' => $tahun,
                    'bulan' => date('m', strtotime($tahun . '-' . $i)),
                    'jml_pelanggaran' => '0',
                    'kereta_tempelan_bak_terbuka' => '0',
                    'mobil_barang_bak_terbuka' => '0',
                    'mobil_barang_bak_tertutup' => '0',
                    'mobil_penarik' => '0',
                    'mobil_tangki' => '0',
                    'kereta_tempelan' => '0',
                    'kendaraan_khusus' => '0',
                    'kereta_gandeng_bak_tertutup' => '0',
                    'kereta_gandeng_bak_terbuka' => '0',
                    'kendaraan_bermotor_roda_tiga' => '0'
                ];
            };

            foreach ($query as $d) {
                $b = (int) $d->bulan - 1;
                //$bulanArr[$b] = Carbon=>:parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                $data[$b] = $d;
            }
        }

        return new Resource(true, __('message.GET_BERHASIL'), $data);
    }

    public function perkategori(Request $request)
    {
        $interval = $request->input('interval');
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');

        if ($interval === 1 || $interval === '1') {
            $query = DB::table('vr_total_pelanggaran_perkategori')
                ->whereYear('waktu', $tahun)
                ->whereMonth('waktu', $bulan)
                ->get();
        } else {
            $query = DB::table('vr_total_pelanggaran_perkategori')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(perusahaan) AS perusahaan,
                sum(perseorangan) AS perseorangan
            ")
                ->whereYear('waktu', $tahun)
                ->groupBy('bulan', 'tahun')
                ->orderBy('bulan', 'asc')
                ->get();
        }

        return new Resource(true, __('message.GET_BERHASIL'), $query);
    }

    public function perjenispelanggaran(Request $request)
    {
        $interval = $request->input('interval');
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');

        if ($interval === 1 || $interval === '1') {
            $data = [];
            $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

            $query = DB::table('vr_lap_perjenis_pelanggaran')
                ->whereYear('waktu', $tahun)
                ->whereMonth('waktu', $bulan)
                ->get();

            for ($i = 1; $i < $jmlHari + 1; $i++) {
                $data[] = [
                    'waktu' => date('Y-m-d', strtotime($tahun . '-' . $bulan . '-' . $i)),
                    'daya_angkut' => '0',
                    'dimensi' => '0',
                    'persyaratan_teknis' => '0',
                    'dokumen' => '0',
                    'tata_cara_muat' => '0',
                    'kelas_jalan' => '0',
                    'rambu_lalu_lintas' => '0'
                ];
            };
            foreach ($query as $d) {
                $b = (int) date('d', strtotime($d->waktu)) - 1;
                //$bulanArr[$b] = Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                $data[$b] = $d;
            }
        } else {
            $data = [];
            $query = DB::table('vr_lap_perjenis_pelanggaran')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(daya_angkut) as daya_angkut,
                sum(dimensi) AS dimensi,
                sum(persyaratan_teknis) AS persyaratan_teknis,
                sum(dokumen) AS dokumen,
                sum(tata_cara_muat) AS tata_cara_muat,
                sum(kelas_jalan) AS kelas_jalan,
                sum(rambu_lalu_lintas) AS rambu_lalu_lintas
            ")
                ->whereYear('waktu', $tahun)
                ->groupBy('bulan', 'tahun')
                ->orderBy('bulan', 'asc')
                ->get();

            for ($i = 1; $i < 13; $i++) {
                $data[] = [
                    'tahun' => $tahun,
                    'bulan' => date('m', strtotime($tahun . '-' . $i)),
                    'daya_angkut' => '0',
                    'dimensi' => '0',
                    'persyaratan_teknis' => '0',
                    'dokumen' => '0',
                    'tata_cara_muat' => '0',
                    'kelas_jalan' => '0',
                    'rambu_lalu_lintas' => '0'
                ];
            };

            foreach ($query as $d) {
                $b = (int) $d->bulan - 1;
                //$bulanArr[$b] = Carbon=>:parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                $data[$b] = $d;
            }
        }

        return new Resource(true, __('message.GET_BERHASIL'), $data);
    }

    public function perberat(Request $request)
    {
        $interval = $request->input('interval');
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');

        $data = [];
        if ($interval === 1 || $interval === '1') {
            $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

            $query = DB::table('vr_lap_pelanggaran_range_berat')->selectRaw("
                waktu,
                sum(range_5_20) AS range_5_20,
                sum(range_21_40) AS range_21_40,
                sum(range_41_60) AS range_41_60,
                sum(range_61_80) AS range_61_80,
                sum(range_81_100) AS range_81_100,
                sum(range_up_100) AS range_up_100
            ")
                ->whereYear('waktu', $tahun)
                ->whereMonth('waktu', $bulan)
                ->groupBy('waktu')
                ->orderBy('waktu')
                ->get();

            for ($i = 1; $i < $jmlHari + 1; $i++) {
                $data[] = [
                    'waktu' => date('Y-m-d', strtotime($tahun . '-' . $bulan . '-' . $i)),
                    'range_5_20' => '0',
                    'range_21_40' => '0',
                    'range_41_60' => '0',
                    'range_61_80' => '0',
                    'range_81_100' => '0',
                    'range_up_100' => '0'
                ];
            };
            foreach ($query as $d) {
                $b = (int) date('d', strtotime($d->waktu)) - 1;
                //$bulanArr[$b] = Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                $data[$b] = $d;
            }
        } else {
            $query = DB::table('vr_lap_pelanggaran_range_berat')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(range_5_20) AS range_5_20,
                sum(range_21_40) AS range_21_40,
                sum(range_41_60) AS range_41_60,
                sum(range_61_80) AS range_61_80,
                sum(range_81_100) AS range_81_100,
                sum(range_up_100) AS range_up_100
            ")
                ->whereYear('waktu', $tahun)
                ->groupBy('bulan', 'tahun')
                ->orderBy('bulan', 'asc')
                ->get();

            for ($i = 1; $i < 13; $i++) {
                $data[] = [
                    'tahun' => $tahun,
                    'bulan' => date('m', strtotime($tahun . '-' . $i)),
                    'range_5_20' => '0',
                    'range_21_40' => '0',
                    'range_41_60' => '0',
                    'range_61_80' => '0',
                    'range_81_100' => '0',
                    'range_up_100' => '0'
                ];
            };

            foreach ($query as $d) {
                $b = (int) $d->bulan - 1;
                //$bulanArr[$b] = Carbon=>:parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                $data[$b] = $d;
            }
        }

        return new Resource(true, __('message.GET_BERHASIL'), $data);
    }

    public function all(Request $request)
    {
        $interval = $request->input('interval');
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');

        if ($interval === 1 || $interval === '1') {
            $query = DB::table('vr_total_pelanggaran_all')
                ->whereYear('waktu', $tahun)
                ->whereMonth('waktu', $bulan)
                ->get();
        } else {
            $query = DB::table('vr_total_pelanggaran_all')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(jml_capture) AS jml_capture,
                sum(jml_verifikasi) AS jml_verifikasi,
                sum(jml_archive) AS jml_archive,
                sum(jml_pelanggaran) AS jml_pelanggaran,
                sum(kereta_tempelan_bak_terbuka) AS kereta_tempelan_bak_terbuka,
                sum(mobil_barang_bak_terbuka) AS mobil_barang_bak_terbuka,
                sum(mobil_barang_bak_tertutup) AS mobil_barang_bak_tertutup,
                sum(mobil_penarik) AS mobil_penarik,
                sum(mobil_tangki) AS mobil_tangki,
                sum(kereta_tempelan) AS kereta_tempelan,
                sum(kendaraan_khusus) AS kendaraan_khusus,
                sum(kereta_gandeng_bak_tertutup) AS kereta_gandeng_bak_tertutup,
                sum(kereta_gandeng_bak_terbuka) AS kereta_gandeng_bak_terbuka,
                sum(kendaraan_bermotor_roda_tiga) AS kendaraan_bermotor_roda_tiga,
                sum(perusahaan) AS perusahaan,
                sum(perseorangan) AS perseorangan,
                sum(range_5_20) AS range_5_20,
                sum(range_21_40) AS range_21_40,
                sum(range_41_60) AS range_41_60,
                sum(range_61_80) AS range_61_80,
                sum(range_81_100) AS range_81_100,
                sum(range_up_100) AS range_up_100
            ")
                ->whereYear('waktu', $tahun)
                ->groupBy('bulan', 'tahun')
                ->orderBy('bulan', 'asc')
                ->get();
        }

        return new Resource(true, __('message.GET_BERHASIL'), $query);
    }

    public function printpdf(Request $request)
    {
        $jenis = $request->input('jenis');
        $interval = $request->input('interval');
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');
        $kuppkb = $request->kuppkb ?? env('MIX_KODE_UPPKB');

        $head = DB::table('jt_lokasi_uppkb')
            ->join('jt_bptd', 'jt_lokasi_uppkb.bptd_id', '=', 'jt_bptd.id')
            ->select('jt_lokasi_uppkb.*', 'jt_bptd.nama as bptd')
            ->where('jt_lokasi_uppkb.kode', $kuppkb)
            ->get();

        if ($jenis === 1 || $jenis === '1') {
            if ($interval === 1 || $interval === '1') {
                $data = [];
                $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

                $query = DB::table('vr_total_pelanggaran_perjenis_kendaraan')
                    ->whereYear('waktu', $tahun)
                    ->whereMonth('waktu', $bulan)
                    ->get();

                for ($i = 1; $i < $jmlHari + 1; $i++) {
                    $data[] = (object) [
                        'waktu' => date('d-m-Y', strtotime($tahun . '-' . $bulan . '-' . $i)),
                        'jml_pelanggaran' => '0',
                        'kereta_tempelan_bak_terbuka' => '0',
                        'mobil_barang_bak_terbuka' => '0',
                        'mobil_barang_bak_tertutup' => '0',
                        'mobil_penarik' => '0',
                        'mobil_tangki' => '0',
                        'kereta_tempelan' => '0',
                        'kendaraan_khusus' => '0',
                        'kereta_gandeng_bak_tertutup' => '0',
                        'kereta_gandeng_bak_terbuka' => '0',
                        'kendaraan_bermotor_roda_tiga' => '0'
                    ];
                };
                foreach ($query as $d) {
                    $b = (int) date('d', strtotime($d->waktu)) - 1;
                    //$bulanArr[$b] = Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => date('d-m-Y', strtotime($d->waktu)),
                        'jml_pelanggaran' => $d->jml_pelanggaran,
                        'kereta_tempelan_bak_terbuka' => $d->kereta_tempelan_bak_terbuka,
                        'mobil_barang_bak_terbuka' => $d->mobil_barang_bak_terbuka,
                        'mobil_barang_bak_tertutup' => $d->mobil_barang_bak_tertutup,
                        'mobil_penarik' => $d->mobil_penarik,
                        'mobil_tangki' => $d->mobil_tangki,
                        'kereta_tempelan' => $d->kereta_tempelan,
                        'kendaraan_khusus' => $d->kendaraan_khusus,
                        'kereta_gandeng_bak_tertutup' => $d->kereta_gandeng_bak_tertutup,
                        'kereta_gandeng_bak_terbuka' => $d->kereta_gandeng_bak_terbuka,
                        'kendaraan_bermotor_roda_tiga' => $d->kendaraan_bermotor_roda_tiga
                    ];
                }

                $row = [
                    'judul' => 'Laporan Rekapitulasi Data Pelanggaran Perjenis Kendaraan',
                    'interval' => 'Bulan',
                    'tgl' => Carbon::parse($tahun . '-' . $bulan)->isoFormat('MMMM, YYYY'),
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'jk' => $data,
                    'head' => $head,

                ];

                $namaFile = 'laporan_perjenis_kendaraan_bulan_' . Carbon::parse($tahun . '-' . $bulan)->isoFormat('MMMM-YYYY');

                $uploadFolder = public_path() . '/laporan/rekap';
                $pdf = PDF::loadView('rekap_data_perjenis', $row);
                $pdf->setPaper('A4', 'landscape');
                //return $pdf->stream('laporan.pdf');
                $pdf->setWarnings(false)->save('laporan/rekap/' . $namaFile . '.pdf');
                $url = url('/') . '/laporan/rekap/' . $namaFile . '.pdf';
                $res = [
                    'nama_file' => $namaFile . '.pdf',
                    'url_file' => $url,
                ];
            } else {
                $data = [];
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
                    ->whereYear('waktu', $tahun)
                    ->groupBy('bulan', 'tahun')
                    ->orderBy('bulan', 'asc')
                    ->get();

                for ($i = 1; $i < 13; $i++) {
                    $data[] = (object) [
                        'waktu' => Carbon::parse($tahun . '-' . $i)->isoFormat('MMMM'),
                        'jml_pelanggaran' => '0',
                        'kereta_tempelan_bak_terbuka' => '0',
                        'mobil_barang_bak_terbuka' => '0',
                        'mobil_barang_bak_tertutup' => '0',
                        'mobil_penarik' => '0',
                        'mobil_tangki' => '0',
                        'kereta_tempelan' => '0',
                        'kendaraan_khusus' => '0',
                        'kereta_gandeng_bak_tertutup' => '0',
                        'kereta_gandeng_bak_terbuka' => '0',
                        'kendaraan_bermotor_roda_tiga' => '0'
                    ];
                };

                foreach ($query as $d) {
                    $b = (int) $d->bulan - 1;
                    //$bulanArr[$b] = Carbon=>:parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM'),
                        'jml_pelanggaran' => $d->jml_pelanggaran,
                        'kereta_tempelan_bak_terbuka' => $d->kereta_tempelan_bak_terbuka,
                        'mobil_barang_bak_terbuka' => $d->mobil_barang_bak_terbuka,
                        'mobil_barang_bak_tertutup' => $d->mobil_barang_bak_tertutup,
                        'mobil_penarik' => $d->mobil_penarik,
                        'mobil_tangki' => $d->mobil_tangki,
                        'kereta_tempelan' => $d->kereta_tempelan,
                        'kendaraan_khusus' => $d->kendaraan_khusus,
                        'kereta_gandeng_bak_tertutup' => $d->kereta_gandeng_bak_tertutup,
                        'kereta_gandeng_bak_terbuka' => $d->kereta_gandeng_bak_terbuka,
                        'kendaraan_bermotor_roda_tiga' => $d->kendaraan_bermotor_roda_tiga
                    ];
                };

                $row = [
                    'judul' => 'Laporan Rekapitulasi Data Pelanggaran Perjenis Kendaraan',
                    'interval' => 'Tahun',
                    'tgl' => 'Tahun ' . $tahun,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'jk' => $data,
                    'head' => $head,

                ];

                $namaFile = 'laporan_perjenis_kendaraan_tahun_' . $tahun;

                $uploadFolder = public_path() . '/laporan/rekap';
                $pdf = PDF::loadView('rekap_data_perjenis', $row);
                $pdf->setPaper('A4', 'landscape');
                //return $pdf->stream('laporan.pdf');
                $pdf->setWarnings(false)->save('laporan/rekap/' . $namaFile . '.pdf');
                $url = url('/') . '/laporan/rekap/' . $namaFile . '.pdf';
                $res = [
                    'nama_file' => $namaFile . '.pdf',
                    'url_file' => $url,
                ];
            }
        } elseif ($jenis === 2 || $jenis === '2') {
            if ($interval === 1 || $interval === '1') {
                $data = [];
                $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

                $query = DB::table('vr_lap_pelanggaran_range_berat')->selectRaw("
                waktu,
                sum(range_5_20) AS range_5_20,
                sum(range_21_40) AS range_21_40,
                sum(range_41_60) AS range_41_60,
                sum(range_61_80) AS range_61_80,
                sum(range_81_100) AS range_81_100,
                sum(range_up_100) AS range_up_100
            ")
                    ->whereYear('waktu', $tahun)
                    ->whereMonth('waktu', $bulan)
                    ->groupBy('waktu')
                    ->orderBy('waktu')
                    ->get();

                for ($i = 1; $i < $jmlHari + 1; $i++) {
                    $data[] = (object) [
                        'waktu' => date('d-m-Y', strtotime($tahun . '-' . $bulan . '-' . $i)),
                        'range_5_20' => '0',
                        'range_21_40' => '0',
                        'range_41_60' => '0',
                        'range_61_80' => '0',
                        'range_81_100' => '0',
                        'range_up_100' => '0'
                    ];
                };
                foreach ($query as $d) {
                    $b = (int) date('d', strtotime($d->waktu)) - 1;
                    //$bulanArr[$b] = Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => date('d-m-Y', strtotime($d->waktu)),
                        'range_5_20' => $d->range_5_20,
                        'range_21_40' => $d->range_21_40,
                        'range_41_60' => $d->range_41_60,
                        'range_61_80' => $d->range_61_80,
                        'range_81_100' => $d->range_81_100,
                        'range_up_100' => $d->range_up_100
                    ];
                }

                $row = [
                    'judul' => 'Laporan Rekapitulasi Data Pelanggaran Kelebihan Muatan',
                    'interval' => 'Bulan',
                    'tgl' => Carbon::parse($tahun . '-' . $bulan)->isoFormat('MMMM, YYYY'),
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'km' => $data,
                    'head' => $head,

                ];

                $namaFile = 'laporan_kelebihan_muatan_bulan_' . Carbon::parse($tahun . '-' . $bulan)->isoFormat('MMMM-YYYY');

                $uploadFolder = public_path() . '/laporan/rekap';
                $pdf = PDF::loadView('rekap_data_berat', $row);
                $pdf->setPaper('A4', 'landscape');
                //return $pdf->stream('laporan.pdf');
                $pdf->setWarnings(false)->save('laporan/rekap/' . $namaFile . '.pdf');
                $url = url('/') . '/laporan/rekap/' . $namaFile . '.pdf';
                $res = [
                    'nama_file' => $namaFile . '.pdf',
                    'url_file' => $url,
                ];
            } else {
                $data = [];
                $query = DB::table('vr_lap_pelanggaran_range_berat')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) AS bulan,
                sum(range_5_20) AS range_5_20,
                sum(range_21_40) AS range_21_40,
                sum(range_41_60) AS range_41_60,
                sum(range_61_80) AS range_61_80,
                sum(range_81_100) AS range_81_100,
                sum(range_up_100) AS range_up_100
            ")
                    ->whereYear('waktu', $tahun)
                    ->groupBy('bulan', 'tahun')
                    ->orderBy('bulan', 'asc')
                    ->get();

                for ($i = 1; $i < 13; $i++) {
                    $data[] = (object) [
                        'waktu' => Carbon::parse($tahun . '-' . $i)->isoFormat('MMMM'),
                        'range_5_20' => '0',
                        'range_21_40' => '0',
                        'range_41_60' => '0',
                        'range_61_80' => '0',
                        'range_81_100' => '0',
                        'range_up_100' => '0'
                    ];
                };

                foreach ($query as $d) {
                    $b = (int) $d->bulan - 1;
                    //$bulanArr[$b] = Carbon=>:parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM'),
                        'range_5_20' => $d->range_5_20,
                        'range_21_40' => $d->range_21_40,
                        'range_41_60' => $d->range_41_60,
                        'range_61_80' => $d->range_61_80,
                        'range_81_100' => $d->range_81_100,
                        'range_up_100' => $d->range_up_100
                    ];
                }

                $row = [
                    'judul' => 'Laporan Rekapitulasi Data Pelanggaran Kelebihan Muatan',
                    'interval' => 'Tahun',
                    'tgl' => 'Tahun ' . $tahun,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'km' => $data,
                    'head' => $head,

                ];

                $namaFile = 'laporan_kelebihan_muatan_tahun_' . $tahun;

                $uploadFolder = public_path() . '/laporan/rekap';
                $pdf = PDF::loadView('rekap_data_berat', $row);
                $pdf->setPaper('A4', 'landscape');
                //return $pdf->stream('laporan.pdf');
                $pdf->setWarnings(false)->save('laporan/rekap/' . $namaFile . '.pdf');
                $url = url('/') . '/laporan/rekap/' . $namaFile . '.pdf';
                $res = [
                    'nama_file' => $namaFile . '.pdf',
                    'url_file' => $url,
                ];
            }
        } else {
            if ($interval === 1 || $interval === '1') {
                $data = [];
                $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

                $query = DB::table('vr_lap_perjenis_pelanggaran')
                    ->whereYear('waktu', $tahun)
                    ->whereMonth('waktu', $bulan)
                    ->get();

                for ($i = 1; $i < $jmlHari + 1; $i++) {
                    $data[] = (object) [
                        'waktu' => date('d-m-Y', strtotime($tahun . '-' . $bulan . '-' . $i)),
                        'daya_angkut' => '0',
                        'dimensi' => '0',
                        'persyaratan_teknis' => '0',
                        'dokumen' => '0',
                        'tata_cara_muat' => '0',
                        'kelas_jalan' => '0',
                        'rambu_lalu_lintas' => '0'
                    ];
                };
                foreach ($query as $d) {
                    $b = (int) date('d', strtotime($d->waktu)) - 1;
                    //$bulanArr[$b] = Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => date('d-m-Y', strtotime($d->waktu)),
                        'daya_angkut' => $d->daya_angkut,
                        'dimensi' => $d->dimensi,
                        'persyaratan_teknis' => $d->persyaratan_teknis,
                        'dokumen' => $d->dokumen,
                        'tata_cara_muat' => $d->tata_cara_muat,
                        'kelas_jalan' => $d->kelas_jalan,
                        'rambu_lalu_lintas' => $d->rambu_lalu_lintas
                    ];
                }

                $row = [
                    'judul' => 'Laporan Rekapitulasi Data Pelanggaran Perjenis Pelanggaran',
                    'interval' => 'Bulan',
                    'tgl' => Carbon::parse($tahun . '-' . $bulan)->isoFormat('MMMM, YYYY'),
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'jp' => $data,
                    'head' => $head,

                ];

                $namaFile = 'laporan_perjenis_pelanggaran_bulan_' . Carbon::parse($tahun . '-' . $bulan)->isoFormat('MMMM-YYYY');

                $uploadFolder = public_path() . '/laporan/rekap';
                $pdf = PDF::loadView('rekap_data_perjenispelanggaran', $row);
                $pdf->setPaper('A4', 'landscape');
                //return $pdf->stream('laporan.pdf');
                $pdf->setWarnings(false)->save('laporan/rekap/' . $namaFile . '.pdf');
                $url = url('/') . '/laporan/rekap/' . $namaFile . '.pdf';
                $res = [
                    'nama_file' => $namaFile . '.pdf',
                    'url_file' => $url,
                ];
            } else {
                $data = [];
                $query = DB::table('vr_lap_perjenis_pelanggaran')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(daya_angkut) as daya_angkut,
                sum(dimensi) AS dimensi,
                sum(persyaratan_teknis) AS persyaratan_teknis,
                sum(dokumen) AS dokumen,
                sum(tata_cara_muat) AS tata_cara_muat,
                sum(kelas_jalan) AS kelas_jalan,
                sum(rambu_lalu_lintas) AS rambu_lalu_lintas
            ")
                    ->whereYear('waktu', $tahun)
                    ->groupBy('bulan', 'tahun')
                    ->orderBy('bulan', 'asc')
                    ->get();

                for ($i = 1; $i < 13; $i++) {
                    $data[] = (object) [
                        'waktu' => Carbon::parse($tahun . '-' . $i)->isoFormat('MMMM'),
                        'daya_angkut' => '0',
                        'dimensi' => '0',
                        'persyaratan_teknis' => '0',
                        'dokumen' => '0',
                        'tata_cara_muat' => '0',
                        'kelas_jalan' => '0',
                        'rambu_lalu_lintas' => '0'
                    ];
                };

                foreach ($query as $d) {
                    $b = (int) $d->bulan - 1;
                    //$bulanArr[$b] = Carbon=>:parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM'),
                        'daya_angkut' => $d->daya_angkut,
                        'dimensi' => $d->dimensi,
                        'persyaratan_teknis' => $d->persyaratan_teknis,
                        'dokumen' => $d->dokumen,
                        'tata_cara_muat' => $d->tata_cara_muat,
                        'kelas_jalan' => $d->kelas_jalan,
                        'rambu_lalu_lintas' => $d->rambu_lalu_lintas
                    ];
                };

                $row = [
                    'judul' => 'Laporan Rekapitulasi Data Pelanggaran Perjenis Pelanggaran',
                    'interval' => 'Tahun',
                    'tgl' => 'Tahun ' . $tahun,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'jp' => $data,
                    'head' => $head,

                ];

                $namaFile = 'laporan_perjenis_pelanggaran_tahun_' . $tahun;

                $uploadFolder = public_path() . '/laporan/rekap';
                $pdf = PDF::loadView('rekap_data_perjenispelanggaran', $row);
                $pdf->setPaper('A4', 'landscape');
                //return $pdf->stream('laporan.pdf');
                $pdf->setWarnings(false)->save('laporan/rekap/' . $namaFile . '.pdf');
                $url = url('/') . '/laporan/rekap/' . $namaFile . '.pdf';
                $res = [
                    'nama_file' => $namaFile . '.pdf',
                    'url_file' => $url,
                ];
            }
        }

        return new Resource(true, __('message.GET_BERHASIL'), $res);
    }

    public function exportexcel(Request $request)
    {
        $jenis = $request->input('jenis');
        $interval = $request->input('interval');
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');

        if ($jenis === 1 || $jenis === '1') {

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("JTO Verifikator")
                ->setLastModifiedBy("JTO Verifikator");

            $spreadsheet->getActiveSheet(0)->getColumnDimension('A')->setWidth(15);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('B')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('C')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('D')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('E')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('F')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('G')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('H')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('I')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('J')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('K')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('L')->setWidth(25);

            $style = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ),
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '000000')
                ),
                'fill' => array(
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F5B914')
                )
            );
            $spreadsheet->getActiveSheet(0)->getStyle('A1:L1')->applyFromArray($style);

            $sheet = $spreadsheet->getActiveSheet(0);

            $cols = array(
                "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
                "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ",
                "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ",
                "CA", "CB", "CC", "CD", "CE", "CF", "CG", "CH", "CI", "CJ", "CK", "CL", "CM", "CN", "CO", "CP", "CQ", "CR", "CS", "CT", "CU", "CV", "CW", "CX", "CY", "CZ",
                "DA", "DB", "DC", "DD", "DE", "DF", "DG", "DH", "DI", "DJ", "DK", "DL", "DM", "DN", "DO", "DP", "DQ", "DR", "DS", "DT", "DU", "DV", "DW", "DX", "DY", "DZ"
            );

            $val = array(
                "Waktu", "Total Pelanggaran", "Mobil Barang Bak Terbuka", "Mobil Barang Bak Tertutup", "Kereta Tempelan Bak Terbuka", "Mobil Penarik", "Mobil Tangki",
                "Kereta Tempelan", "Kendaraan Khusus", "Kereta Gandeng Bak Terbuka", "Kereta Gandeng Bak Tertutup", "Kendaraan Bermotor Roda Tiga"
            );

            for ($a = 0; $a < 12; $a++) {
                $sheet->setCellValue($cols[$a] . '1', $val[$a]);
            }

            $data = [];

            if ($interval === 1 || $interval === '1') {
                $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

                $query = DB::table('vr_total_pelanggaran_perjenis_kendaraan')
                    ->whereYear('waktu', $tahun)
                    ->whereMonth('waktu', $bulan)
                    ->get();

                for ($i = 1; $i < $jmlHari + 1; $i++) {
                    $data[] = (object) [
                        'waktu' => date('d-m-Y', strtotime($tahun . '-' . $bulan . '-' . $i)),
                        'jml_pelanggaran' => '0',
                        'kereta_tempelan_bak_terbuka' => '0',
                        'mobil_barang_bak_terbuka' => '0',
                        'mobil_barang_bak_tertutup' => '0',
                        'mobil_penarik' => '0',
                        'mobil_tangki' => '0',
                        'kereta_tempelan' => '0',
                        'kendaraan_khusus' => '0',
                        'kereta_gandeng_bak_tertutup' => '0',
                        'kereta_gandeng_bak_terbuka' => '0',
                        'kendaraan_bermotor_roda_tiga' => '0'
                    ];
                };
                foreach ($query as $d) {
                    $b = (int) date('d', strtotime($d->waktu)) - 1;
                    //$bulanArr[$b] = Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => date('d-m-Y', strtotime($d->waktu)),
                        'jml_pelanggaran' => $d->jml_pelanggaran,
                        'kereta_tempelan_bak_terbuka' => $d->kereta_tempelan_bak_terbuka,
                        'mobil_barang_bak_terbuka' => $d->mobil_barang_bak_terbuka,
                        'mobil_barang_bak_tertutup' => $d->mobil_barang_bak_tertutup,
                        'mobil_penarik' => $d->mobil_penarik,
                        'mobil_tangki' => $d->mobil_tangki,
                        'kereta_tempelan' => $d->kereta_tempelan,
                        'kendaraan_khusus' => $d->kendaraan_khusus,
                        'kereta_gandeng_bak_tertutup' => $d->kereta_gandeng_bak_tertutup,
                        'kereta_gandeng_bak_terbuka' => $d->kereta_gandeng_bak_terbuka,
                        'kendaraan_bermotor_roda_tiga' => $d->kendaraan_bermotor_roda_tiga
                    ];
                }

                $namaFile = 'laporan_perjenis_kendaraan_bulan_' . Carbon::parse($tahun . '-' . $bulan)->isoFormat('MMMM-YYYY');
            } else {
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
                    ->whereYear('waktu', $tahun)
                    ->groupBy('bulan', 'tahun')
                    ->orderBy('bulan', 'asc')
                    ->get();

                for ($i = 1; $i < 13; $i++) {
                    $data[] = (object) [
                        'waktu' => Carbon::parse($tahun . '-' . $i)->isoFormat('MMMM'),
                        'jml_pelanggaran' => '0',
                        'kereta_tempelan_bak_terbuka' => '0',
                        'mobil_barang_bak_terbuka' => '0',
                        'mobil_barang_bak_tertutup' => '0',
                        'mobil_penarik' => '0',
                        'mobil_tangki' => '0',
                        'kereta_tempelan' => '0',
                        'kendaraan_khusus' => '0',
                        'kereta_gandeng_bak_tertutup' => '0',
                        'kereta_gandeng_bak_terbuka' => '0',
                        'kendaraan_bermotor_roda_tiga' => '0'
                    ];
                };

                foreach ($query as $d) {
                    $b = (int) $d->bulan - 1;
                    //$bulanArr[$b] = Carbon=>:parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM'),
                        'jml_pelanggaran' => $d->jml_pelanggaran,
                        'kereta_tempelan_bak_terbuka' => $d->kereta_tempelan_bak_terbuka,
                        'mobil_barang_bak_terbuka' => $d->mobil_barang_bak_terbuka,
                        'mobil_barang_bak_tertutup' => $d->mobil_barang_bak_tertutup,
                        'mobil_penarik' => $d->mobil_penarik,
                        'mobil_tangki' => $d->mobil_tangki,
                        'kereta_tempelan' => $d->kereta_tempelan,
                        'kendaraan_khusus' => $d->kendaraan_khusus,
                        'kereta_gandeng_bak_tertutup' => $d->kereta_gandeng_bak_tertutup,
                        'kereta_gandeng_bak_terbuka' => $d->kereta_gandeng_bak_terbuka,
                        'kendaraan_bermotor_roda_tiga' => $d->kendaraan_bermotor_roda_tiga
                    ];
                };

                $namaFile = 'laporan_perjenis_kendaraan_tahun_' . $tahun;
            }

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
            $baris = 2;
            foreach ($data as $row) {

                $sheet->setCellValue('A' . $baris, $row->waktu);
                $sheet->setCellValue('B' . $baris, $row->jml_pelanggaran ?? 0);
                $sheet->setCellValue('C' . $baris, $row->mobil_barang_bak_terbuka ?? 0);
                $sheet->setCellValue('D' . $baris, $row->mobil_barang_bak_tertutup ?? 0);
                $sheet->setCellValue('E' . $baris, $row->kereta_tempelan_bak_terbuka ?? 0);
                $sheet->setCellValue('F' . $baris, $row->mobil_penarik ?? 0);
                $sheet->setCellValue('G' . $baris, $row->mobil_tangki ?? 0);
                $sheet->setCellValue('H' . $baris, $row->kereta_tempelan ?? 0);
                $sheet->setCellValue('I' . $baris, $row->kendaraan_khusus ?? 0);
                $sheet->setCellValue('J' . $baris, $row->kereta_gandeng_bak_tertutup ?? 0);
                $sheet->setCellValue('K' . $baris, $row->kereta_gandeng_bak_terbuka ?? 0);
                $sheet->setCellValue('L' . $baris, $row->kendaraan_bermotor_roda_tiga ?? 0);

                $jml_pelanggaran += $row->jml_pelanggaran;
                $mobil_barang_bak_terbuka += $row->mobil_barang_bak_terbuka;
                $mobil_barang_bak_tertutup += $row->mobil_barang_bak_tertutup;
                $kereta_tempelan_bak_terbuka += $row->kereta_tempelan_bak_terbuka;
                $mobil_penarik += $row->mobil_penarik;
                $mobil_tangki += $row->mobil_tangki;
                $kereta_tempelan += $row->kereta_tempelan;
                $kendaraan_khusus += $row->kendaraan_khusus;
                $kereta_gandeng_bak_tertutup += $row->kereta_gandeng_bak_tertutup;
                $kereta_gandeng_bak_terbuka += $row->kereta_gandeng_bak_terbuka;
                $kendaraan_bermotor_roda_tiga += $row->kendaraan_bermotor_roda_tiga;

                $baris++;
            };
            $sheet->setCellValue('A' . $baris, 'TOTAL');
            $sheet->setCellValue('B' . $baris, $jml_pelanggaran);
            $sheet->setCellValue('C' . $baris, $mobil_barang_bak_terbuka);
            $sheet->setCellValue('D' . $baris, $mobil_barang_bak_tertutup);
            $sheet->setCellValue('E' . $baris, $kereta_tempelan_bak_terbuka);
            $sheet->setCellValue('F' . $baris, $mobil_penarik);
            $sheet->setCellValue('G' . $baris, $mobil_tangki);
            $sheet->setCellValue('H' . $baris, $kereta_tempelan);
            $sheet->setCellValue('I' . $baris, $kendaraan_khusus);
            $sheet->setCellValue('J' . $baris, $kereta_gandeng_bak_tertutup);
            $sheet->setCellValue('K' . $baris, $kereta_gandeng_bak_terbuka);
            $sheet->setCellValue('L' . $baris, $kendaraan_bermotor_roda_tiga);

            $sheet->getStyle('A' . $baris . ':L' . $baris)->getFont()->setBold(true);

            $styleAll = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ),
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN
                    )
                )
            );
            $sheet->getStyle('A1:L' . $baris)->applyFromArray($styleAll);

            $writer = new Xlsx($spreadsheet);
            $writer->save('laporan/rekap/' . $namaFile . '.xlsx');
            $url = url('/') . '/laporan/rekap/' . $namaFile . '.xlsx';
            $res = [
                'nama_file' => $namaFile . '.xlsx',
                'url_file' => $url,
            ];
        } elseif($jenis === 2 || $jenis === '2') {

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("JTO Verifikator")
                ->setLastModifiedBy("JTO Verifikator");

            $spreadsheet->getActiveSheet(0)->getColumnDimension('A')->setWidth(15);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('C')->setWidth(15);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('D')->setWidth(15);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('E')->setWidth(15);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('F')->setWidth(15);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('G')->setWidth(15);

            $style = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ),
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '000000')
                ),
                'fill' => array(
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F5B914')
                )
            );
            $spreadsheet->getActiveSheet(0)->getStyle('A1:G1')->applyFromArray($style);

            $sheet = $spreadsheet->getActiveSheet(0);

            $cols = array(
                "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
                "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ",
                "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ",
                "CA", "CB", "CC", "CD", "CE", "CF", "CG", "CH", "CI", "CJ", "CK", "CL", "CM", "CN", "CO", "CP", "CQ", "CR", "CS", "CT", "CU", "CV", "CW", "CX", "CY", "CZ",
                "DA", "DB", "DC", "DD", "DE", "DF", "DG", "DH", "DI", "DJ", "DK", "DL", "DM", "DN", "DO", "DP", "DQ", "DR", "DS", "DT", "DU", "DV", "DW", "DX", "DY", "DZ"
            );

            $val = array(
                "Waktu", "5 s/d 20", "21 s/d 40", "41 s/d 60", "61 s/d 80", "81 s/d 100", ">100"
            );

            for ($a = 0; $a < 7; $a++) {
                $sheet->setCellValue($cols[$a] . '1', $val[$a]);
            }
            $data = [];

            if ($interval === 1 || $interval === '1') {
                $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

                $query = DB::table('vr_lap_pelanggaran_range_berat')->selectRaw("
                waktu,
                sum(range_5_20) AS range_5_20,
                sum(range_21_40) AS range_21_40,
                sum(range_41_60) AS range_41_60,
                sum(range_61_80) AS range_61_80,
                sum(range_81_100) AS range_81_100,
                sum(range_up_100) AS range_up_100
            ")
                    ->whereYear('waktu', $tahun)
                    ->whereMonth('waktu', $bulan)
                    ->groupBy('waktu')
                    ->orderBy('waktu')
                    ->get();

                for ($i = 1; $i < $jmlHari + 1; $i++) {
                    $data[] = (object) [
                        'waktu' => date('d-m-Y', strtotime($tahun . '-' . $bulan . '-' . $i)),
                        'range_5_20' => '0',
                        'range_21_40' => '0',
                        'range_41_60' => '0',
                        'range_61_80' => '0',
                        'range_81_100' => '0',
                        'range_up_100' => '0'
                    ];
                };
                foreach ($query as $d) {
                    $b = (int) date('d', strtotime($d->waktu)) - 1;
                    //$bulanArr[$b] = Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => date('d-m-Y', strtotime($d->waktu)),
                        'range_5_20' => $d->range_5_20,
                        'range_21_40' => $d->range_21_40,
                        'range_41_60' => $d->range_41_60,
                        'range_61_80' => $d->range_61_80,
                        'range_81_100' => $d->range_81_100,
                        'range_up_100' => $d->range_up_100
                    ];
                }

                $row = [
                    'judul' => 'Laporan Rekapitulasi Data Pelanggaran Kelebihan Muatan',
                    'interval' => 'Bulan',
                    'tgl' => Carbon::parse($tahun . '-' . $bulan)->isoFormat('MMMM, YYYY'),
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'km' => $data,

                ];

                $namaFile = 'laporan_kelebihan_muatan_bulan_' . Carbon::parse($tahun . '-' . $bulan)->isoFormat('MMMM-YYYY');
            } else {
                $query = DB::table('vr_lap_pelanggaran_range_berat')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) AS bulan,
                sum(range_5_20) AS range_5_20,
                sum(range_21_40) AS range_21_40,
                sum(range_41_60) AS range_41_60,
                sum(range_61_80) AS range_61_80,
                sum(range_81_100) AS range_81_100,
                sum(range_up_100) AS range_up_100
            ")
                    ->whereYear('waktu', $tahun)
                    ->groupBy('bulan', 'tahun')
                    ->orderBy('bulan', 'asc')
                    ->get();

                for ($i = 1; $i < 13; $i++) {
                    $data[] = (object) [
                        'waktu' => Carbon::parse($tahun . '-' . $i)->isoFormat('MMMM'),
                        'range_5_20' => '0',
                        'range_21_40' => '0',
                        'range_41_60' => '0',
                        'range_61_80' => '0',
                        'range_81_100' => '0',
                        'range_up_100' => '0'
                    ];
                };

                foreach ($query as $d) {
                    $b = (int) $d->bulan - 1;
                    //$bulanArr[$b] = Carbon=>:parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM'),
                        'range_5_20' => $d->range_5_20,
                        'range_21_40' => $d->range_21_40,
                        'range_41_60' => $d->range_41_60,
                        'range_61_80' => $d->range_61_80,
                        'range_81_100' => $d->range_81_100,
                        'range_up_100' => $d->range_up_100
                    ];
                }

                $namaFile = 'laporan_kelebihan_muatan_tahun_' . $tahun;
            }

            $range_5_20 = 0;
            $range_21_40 = 0;
            $range_41_60 = 0;
            $range_61_80 = 0;
            $range_81_100 = 0;
            $range_up_100 = 0;
            $baris = 2;
            foreach ($data as $row) {

                $sheet->setCellValue('A' . $baris, $row->waktu);
                $sheet->setCellValue('B' . $baris, $row->range_5_20 ?? 0);
                $sheet->setCellValue('C' . $baris, $row->range_21_40 ?? 0);
                $sheet->setCellValue('D' . $baris, $row->range_41_60 ?? 0);
                $sheet->setCellValue('E' . $baris, $row->range_61_80 ?? 0);
                $sheet->setCellValue('F' . $baris, $row->range_81_100 ?? 0);
                $sheet->setCellValue('G' . $baris, $row->range_up_100 ?? 0);

                $range_5_20 += $row->range_5_20;
                $range_21_40 += $row->range_21_40;
                $range_41_60 += $row->range_41_60;
                $range_61_80 += $row->range_61_80;
                $range_81_100 += $row->range_81_100;
                $range_up_100 += $row->range_up_100;

                $baris++;
            };
            $sheet->setCellValue('A' . $baris, 'TOTAL');
            $sheet->setCellValue('B' . $baris, $range_5_20);
            $sheet->setCellValue('C' . $baris, $range_21_40);
            $sheet->setCellValue('D' . $baris, $range_41_60);
            $sheet->setCellValue('E' . $baris, $range_61_80);
            $sheet->setCellValue('F' . $baris, $range_81_100);
            $sheet->setCellValue('G' . $baris, $range_up_100);

            $sheet->getStyle('A' . $baris . ':G' . $baris)->getFont()->setBold(true);

            $styleAll = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ),
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN
                    )
                )
            );
            $sheet->getStyle('A1:G' . $baris)->applyFromArray($styleAll);

            $writer = new Xlsx($spreadsheet);
            $writer->save('laporan/rekap/' . $namaFile . '.xlsx');
            $url = url('/') . '/laporan/rekap/' . $namaFile . '.xlsx';
            $res = [
                'nama_file' => $namaFile . '.xlsx',
                'url_file' => $url,
            ];
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("JTO Verifikator")
                ->setLastModifiedBy("JTO Verifikator");

            $spreadsheet->getActiveSheet(0)->getColumnDimension('A')->setWidth(15);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('B')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('C')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('D')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('E')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('F')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('G')->setWidth(25);
            $spreadsheet->getActiveSheet(0)->getColumnDimension('H')->setWidth(25);

            $style = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ),
                'font' => array(
                    'bold' => true,
                    'color' => array('rgb' => '000000')
                ),
                'fill' => array(
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F5B914')
                )
            );
            $spreadsheet->getActiveSheet(0)->getStyle('A1:H1')->applyFromArray($style);

            $sheet = $spreadsheet->getActiveSheet(0);

            $cols = array(
                "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
                "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ",
                "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ",
                "CA", "CB", "CC", "CD", "CE", "CF", "CG", "CH", "CI", "CJ", "CK", "CL", "CM", "CN", "CO", "CP", "CQ", "CR", "CS", "CT", "CU", "CV", "CW", "CX", "CY", "CZ",
                "DA", "DB", "DC", "DD", "DE", "DF", "DG", "DH", "DI", "DJ", "DK", "DL", "DM", "DN", "DO", "DP", "DQ", "DR", "DS", "DT", "DU", "DV", "DW", "DX", "DY", "DZ"
            );

            $val = array(
                "Waktu", "Daya Angkut", "Dimensi", "Persyaratan Teknis", "Dokumen", "Tata Cara Muat", "Kelas Jalan",
                "Rambu Lalu Lintas"
            );

            for ($a = 0; $a < 8; $a++) {
                $sheet->setCellValue($cols[$a] . '1', $val[$a]);
            }

            $data = [];

            if ($interval === 1 || $interval === '1') {
                $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

                $query = DB::table('vr_lap_perjenis_pelanggaran')
                ->whereYear('waktu', $tahun)
                ->whereMonth('waktu', $bulan)
                ->get();

                for ($i = 1; $i < $jmlHari + 1; $i++) {
                    $data[] = (object) [
                        'waktu' => date('d-m-Y', strtotime($tahun . '-' . $bulan . '-' . $i)),
                        'daya_angkut' => '0',
                        'dimensi' => '0',
                        'persyaratan_teknis' => '0',
                        'dokumen' => '0',
                        'tata_cara_muat' => '0',
                        'kelas_jalan' => '0',
                        'rambu_lalu_lintas' => '0'
                    ];
                };
                foreach ($query as $d) {
                    $b = (int) date('d', strtotime($d->waktu)) - 1;
                    //$bulanArr[$b] = Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => date('d-m-Y', strtotime($d->waktu)),
                        'daya_angkut' => $d->daya_angkut,
                        'dimensi' => $d->dimensi,
                        'persyaratan_teknis' => $d->persyaratan_teknis,
                        'dokumen' => $d->dokumen,
                        'tata_cara_muat' => $d->tata_cara_muat,
                        'kelas_jalan' => $d->kelas_jalan,
                        'rambu_lalu_lintas' => $d->rambu_lalu_lintas
                    ];
                }

                $namaFile = 'laporan_perjenis_pelanggaran_bulan_' . Carbon::parse($tahun . '-' . $bulan)->isoFormat('MMMM-YYYY');
            } else {
                $query = DB::table('vr_lap_perjenis_pelanggaran')->selectRaw("
                EXTRACT(YEAR FROM waktu) AS tahun,
                EXTRACT(MONTH from waktu) as bulan,
                sum(daya_angkut) as daya_angkut,
                sum(dimensi) AS dimensi,
                sum(persyaratan_teknis) AS persyaratan_teknis,
                sum(dokumen) AS dokumen,
                sum(tata_cara_muat) AS tata_cara_muat,
                sum(kelas_jalan) AS kelas_jalan,
                sum(rambu_lalu_lintas) AS rambu_lalu_lintas
            ")
                    ->whereYear('waktu', $tahun)
                    ->groupBy('bulan', 'tahun')
                    ->orderBy('bulan', 'asc')
                    ->get();

                for ($i = 1; $i < 13; $i++) {
                    $data[] = (object) [
                        'waktu' => Carbon::parse($tahun . '-' . $i)->isoFormat('MMMM'),
                        'daya_angkut' => '0',
                        'dimensi' => '0',
                        'persyaratan_teknis' => '0',
                        'dokumen' => '0',
                        'tata_cara_muat' => '0',
                        'kelas_jalan' => '0',
                        'rambu_lalu_lintas' => '0'
                    ];
                };

                foreach ($query as $d) {
                    $b = (int) $d->bulan - 1;
                    //$bulanArr[$b] = Carbon=>:parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM');
                    $data[$b] = (object) [
                        'waktu' => Carbon::parse($d->tahun . '-' . $d->bulan)->isoFormat('MMMM'),
                        'daya_angkut' => $d->daya_angkut,
                        'dimensi' => $d->dimensi,
                        'persyaratan_teknis' => $d->persyaratan_teknis,
                        'dokumen' => $d->dokumen,
                        'tata_cara_muat' => $d->tata_cara_muat,
                        'kelas_jalan' => $d->kelas_jalan,
                        'rambu_lalu_lintas' => $d->rambu_lalu_lintas
                    ];
                };

                $namaFile = 'laporan_perjenis_pelanggaran_tahun_' . $tahun;
            }

            $daya_angkut = 0;
            $dimensi = 0;
            $persyaratan_teknis = 0;
            $dokumen = 0;
            $tata_cara_muat = 0;
            $kelas_jalan = 0;
            $rambu_lalu_lintas = 0;
            $baris = 2;
            foreach ($data as $row) {

                $sheet->setCellValue('A' . $baris, $row->waktu);
                $sheet->setCellValue('B' . $baris, $row->daya_angkut ?? 0);
                $sheet->setCellValue('C' . $baris, $row->dimensi ?? 0);
                $sheet->setCellValue('D' . $baris, $row->persyaratan_teknis ?? 0);
                $sheet->setCellValue('E' . $baris, $row->dokumen ?? 0);
                $sheet->setCellValue('F' . $baris, $row->tata_cara_muat ?? 0);
                $sheet->setCellValue('G' . $baris, $row->kelas_jalan ?? 0);
                $sheet->setCellValue('H' . $baris, $row->rambu_lalu_lintas ?? 0);

                $daya_angkut += $row->daya_angkut;
                $dimensi += $row->dimensi;
                $persyaratan_teknis += $row->persyaratan_teknis;
                $dokumen += $row->dokumen;
                $tata_cara_muat += $row->tata_cara_muat;
                $kelas_jalan += $row->kelas_jalan;
                $rambu_lalu_lintas += $row->rambu_lalu_lintas;

                $baris++;
            };
            $sheet->setCellValue('A' . $baris, 'TOTAL');
            $sheet->setCellValue('B' . $baris, $daya_angkut);
            $sheet->setCellValue('C' . $baris, $dimensi);
            $sheet->setCellValue('D' . $baris, $persyaratan_teknis);
            $sheet->setCellValue('E' . $baris, $dokumen);
            $sheet->setCellValue('F' . $baris, $tata_cara_muat);
            $sheet->setCellValue('G' . $baris, $kelas_jalan);
            $sheet->setCellValue('H' . $baris, $rambu_lalu_lintas);

            $sheet->getStyle('A' . $baris . ':L' . $baris)->getFont()->setBold(true);

            $styleAll = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ),
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN
                    )
                )
            );
            $sheet->getStyle('A1:H' . $baris)->applyFromArray($styleAll);

            $writer = new Xlsx($spreadsheet);
            $writer->save('laporan/rekap/' . $namaFile . '.xlsx');
            $url = url('/') . '/laporan/rekap/' . $namaFile . '.xlsx';
            $res = [
                'nama_file' => $namaFile . '.xlsx',
                'url_file' => $url,
            ];
        };

        return new Resource(true, __('message.GET_BERHASIL'), $res);
    }
}
