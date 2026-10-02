<?php

namespace App\Http\Controllers;

use App\Exports\PelanggaranExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\PelanggaranResource as Resource;
use App\Models\Archive;
use App\Models\Capture;
use App\Models\DetailCapture;
use App\Models\DetailPasal;
use App\Models\DetailPelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\Pasal;
use App\Models\Pelanggaran as Model;
use App\Models\VerifikasiArchive;
use BaconQrCode\Renderer\Color\Rgb;
use PDF;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
// use App\Models\Kementerian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Generator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PelanggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $sortby = $request->sortby ?? 'desc';
        $page = $request->page - 1 ?? 0;
        $limit = $request->limit ?? 25;
        $tgl_dari = $request->tgl_dari;
        $tgl_sampai = $request->tgl_sampai;
        $no_kendaraan = $request->no_kendaraan;
        $jenis_pelanggaran = $request->jenis_pelanggaran;
        $shift = $request->shift;
        $regu = $request->regu;
        //$total = Model::count();
        $query = Model::with(['device', 'regu', 'shift', 'petugas', 'detailcapture', 'detailpelanggaran', 'detailpasal', 'createdBy', 'updatedBy', 'deletedBy'])->where('is_active', true)->orderBy($sort, $sortby)->skip($page)->limit($limit);
        if ($tgl_dari || $tgl_sampai) {
            $query->whereBetween(DB::raw('DATE(tgl_pelanggaran)'), [$tgl_dari, $tgl_sampai]);
        }
        // if ($no_kendaraan) {
        //     $query->where('no_kendaraan', $no_kendaraan)->where('is_active', true);
        // }
        if ($jenis_pelanggaran) {
            $query->whereHas('detailpelanggaran', function ($q) use ($jenis_pelanggaran) {
                $q->where('jenis_pelanggaran_id', $jenis_pelanggaran);
            });
        }
        if ($shift) {
            $query->where('shift_id', $shift);
        }
        if ($regu) {
            $query->where('regu_id', $regu);
        }

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => count($query->get())
        ];

        $cek_pasal = DB::table('jt_pasal')->where('no_pasal', 287)->get();
        if (count($cek_pasal) < 1) {
            Pasal::create([
                'no_pasal' => 287,
                'pasal' => 'Pasal 287 Ayat 1 Jo Pasal 106 Ayat (4) Huruf A',
                'desk_pasal' => 'Setiap orang yang mengemudikan Kendaraan Bermotor di Jalan yang melanggar aturan perintah atau larangan yang dinyatakan dengan Rambu Lalu Lintas',
                'denda_maks' => 500000,
                'keterangan' => 'Pasal 287 Ayat 1 Jo Pasal 106 Ayat (4) Huruf A',
                'is_active' => true
            ]);
        }

        $data = [];
        foreach ($query->get() as $row) {
            $dt = $row;
            $pasal = '';
            $detailpelanggaran = '';
            $datapasal = $row->detailpasal;
            for ($i = 0; $i < count($datapasal); $i++) {
                if ($i == count($datapasal) - 1) {
                    $pasal .= $datapasal[$i]->desk_pasal;
                } else {
                    $pasal .= $datapasal[$i]->desk_pasal . ', ';
                }
            }
            $dp = $row->detailpelanggaran;
            for ($i = 0; $i < count($dp); $i++) {
                if ($i == count($dp) - 1) {
                    $detailpelanggaran .= $dp[$i]->deskripsi;
                } else {
                    $detailpelanggaran .= $dp[$i]->deskripsi . ', ';
                }
            }
            $dt['detail_pasal'] = $pasal;
            $dt['detail_pelanggaran'] = $detailpelanggaran;

            $data[] = $dt;
        }

        //return collection of posts as a resource
        return new Resource(true, __('message.GET_BERHASIL'), $data, $meta);
        // return Resource::collection($query);
    }

    public function active(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $sortby = $request->sortby ?? 'desc';
        $page = (int) ($request->page ?? 1);
        $limit = (int) ($request->limit ?? 25);
        $tgl_dari = $request->tgl_dari;
        $tgl_sampai = $request->tgl_sampai;
        $no_kendaraan = $request->no_kendaraan;
        $jenis_pelanggaran = $request->jenis_pelanggaran;
        $shift = $request->shift;
        $regu = $request->regu;

        // Build base query untuk filtering (tanpa pagination)
        $baseQuery = Model::where('is_active', true);
        
        if ($tgl_dari || $tgl_sampai) {
            $baseQuery->whereBetween(DB::raw('DATE(tgl_pelanggaran)'), [$tgl_dari, $tgl_sampai]);
        }
        
        if ($jenis_pelanggaran) {
            $baseQuery->whereHas('detailpelanggaran', function ($q) use ($jenis_pelanggaran) {
                $q->where('jenis_pelanggaran_id', $jenis_pelanggaran);
            });
        }
        
        if ($shift) {
            $baseQuery->where('shift_id', $shift);
        }
        
        if ($regu) {
            $baseQuery->where('regu_id', $regu);
        }

        // Hitung total sebelum pagination
        $total = $baseQuery->count();
        $totalPages = ceil($total / $limit);

        // Query dengan pagination dan eager loading
        $skip = ($page - 1) * $limit;
        $query = clone $baseQuery;
        $query = $query->with(['device', 'regu', 'shift', 'petugas', 'detailcapture', 'detailpelanggaran', 'detailpasal', 'createdBy', 'updatedBy', 'deletedBy'])
            ->orderBy($sort, $sortby)
            ->skip($skip)
            ->limit($limit);

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'totalPages' => $totalPages,
            'totalData' => $total
        ];

        $cek_pasal = DB::table('jt_pasal')->where('no_pasal', 287)->get();
        if (count($cek_pasal) < 1) {
            Pasal::create([
                'no_pasal' => 287,
                'pasal' => 'Pasal 287 Ayat 1 Jo Pasal 106 Ayat (4) Huruf A',
                'desk_pasal' => 'Setiap orang yang mengemudikan Kendaraan Bermotor di Jalan yang melanggar aturan perintah atau larangan yang dinyatakan dengan Rambu Lalu Lintas',
                'denda_maks' => 500000,
                'keterangan' => 'Pasal 287 Ayat 1 Jo Pasal 106 Ayat (4) Huruf A',
                'is_active' => true
            ]);
        }

        $data = [];
        foreach ($query->get() as $row) {
            $dt = $row;
            $pasal = '';
            $detailpelanggaran = '';
            $datapasal = $row->detailpasal;
            for ($i = 0; $i < count($datapasal); $i++) {
                if ($i == count($datapasal) - 1) {
                    $pasal .= $datapasal[$i]->desk_pasal;
                } else {
                    $pasal .= $datapasal[$i]->desk_pasal . ', ';
                }
            }
            $dp = $row->detailpelanggaran;
            for ($i = 0; $i < count($dp); $i++) {
                if ($i == count($dp) - 1) {
                    $detailpelanggaran .= $dp[$i]->deskripsi;
                } else {
                    $detailpelanggaran .= $dp[$i]->deskripsi . ', ';
                }
            }
            $dt['detail_pasal'] = $pasal;
            $dt['detail_pelanggaran'] = $detailpelanggaran;

            $data[] = $dt;
        }


        return new Resource(true, __('message.GET_BERHASIL'), $data, $meta);
    }

    public function trush(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $sortby = $request->sortby ?? 'desc';
        $page = $request->page - 1 ?? 0;
        $limit = $request->limit ?? 25;
        $total = Model::count();

        $query = Model::onlyTrashed()->with(['device', 'regu', 'shift', 'petugas', 'detailcapture', 'detailpelanggaran', 'detailpasal', 'createdBy', 'updatedBy', 'deletedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => (int) $total
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $query, $meta);
    }

    public function getbydate(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $sortby = $request->sortby ?? 'desc';
        $page = $request->page - 1 ?? 0;
        $limit = $request->limit;
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $tanggalawal = $tanggal . ' 00:00:01';
        $tanggalakhir = $tanggal . ' 23:59:59';
        $total = Model::count();

        $query = Model::with(['device', 'regu', 'shift', 'detailcapture', 'detailpelanggaran', 'detailpasal', 'createdBy', 'updatedBy', 'deletedBy'])->where('is_active', true)->where('is_verifikasi', false)->whereBetween('tgl_capture', [$tanggalawal, $tanggalakhir])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => (int) $total
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $query, $meta);
    }

    public function exportexcel(Request $request)
    {
        $tgl_dari = $request->tgl_dari ?? date('Y-m-d');
        $tgl_sampai = $request->tgl_sampai ?? date('Y-m-d');
        $jenis_pelanggaran = $request->jenis_pelanggaran;
        $shift = $request->shift;
        $regu = $request->regu;

        $query = Model::with(['device', 'petugas', 'detailcapture', 'detailpelanggaran', 'detailpasal', 'createdBy', 'updatedBy', 'deletedBy'])->where('is_active', true)->orderBy('tgl_pelanggaran', 'asc');
        if ($tgl_dari || $tgl_sampai) {
            $query->whereBetween(DB::raw('DATE(tgl_pelanggaran)'), [$tgl_dari, $tgl_sampai]);
        }
        if ($jenis_pelanggaran) {
            $query->whereHas('detailpelanggaran', function ($q) use ($jenis_pelanggaran) {
                $q->where('jenis_pelanggaran_id', $jenis_pelanggaran);
            });
        }
        if ($shift) {
            $query->where('shift_id', $shift);
        }
        if ($regu) {
            $query->where('regu_id', $regu);
        }

        if (date('Y-m-d', strtotime($tgl_dari)) === date('Y-m-d', strtotime($tgl_sampai))) {
            $namaFile = 'laporan_pelanggaran_' . $tgl_dari;
        } else {
            $namaFile = 'laporan_pelanggaran_' . $tgl_dari . '_' . $tgl_sampai;
        }

        // if (file_exists(public_path() . '/laporan/excel/' . $namaFile . '.xlsx')) {
        //     $url2 = url('/') . '/laporan/excel/' . $namaFile . '.xlsx';
        //     $res2 = [
        //         'nama_file' => $namaFile . '.xlsx',
        //         'url_file' => $url2,
        //     ];

        //     return new Resource(true, 'File Sudah Tersedia', $res2);
        // } else {

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("JTO Verifikator")
            ->setLastModifiedBy("JTO Verifikator");

        // ->setTitle("Office 2007 XLSX Test Document")
        // ->setSubject("Office 2007 XLSX Test Document")
        // ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        // ->setKeywords("office 2007 openxml php")
        // ->setCategory("Test result file");
        $spreadsheet->getActiveSheet(0)->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('D')->setWidth(15);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('F')->setWidth(30);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('G')->setWidth(50);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('H')->setWidth(15);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('I')->setWidth(30);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('J')->setWidth(10);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('K')->setWidth(10);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('L')->setWidth(15);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('M')->setWidth(15);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('N')->setWidth(15);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('O')->setWidth(15);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('P')->setWidth(50);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('Q')->setWidth(50);

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
        $spreadsheet->getActiveSheet(0)->getStyle('A1:Q1')->applyFromArray($style);

        $sheet = $spreadsheet->getActiveSheet(0);

        $cols = array(
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
            "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ",
            "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ",
            "CA", "CB", "CC", "CD", "CE", "CF", "CG", "CH", "CI", "CJ", "CK", "CL", "CM", "CN", "CO", "CP", "CQ", "CR", "CS", "CT", "CU", "CV", "CW", "CX", "CY", "CZ",
            "DA", "DB", "DC", "DD", "DE", "DF", "DG", "DH", "DI", "DJ", "DK", "DL", "DM", "DN", "DO", "DP", "DQ", "DR", "DS", "DT", "DU", "DV", "DW", "DX", "DY", "DZ"
        );

        $val = array(
            "Waktu", "Operator", "Device", "No Kendaraan", "No Uji", "Nama Pemilik",
            "Alamat Pemilik", "Masa Berlaku", "Jenis Kendaraan", "Sumbu", "JBI (Kg)",
            "Berat Timbang (Kg)", "Berat Lebih (Kg)", "Persen Lebih (%)", "Toleransi (%)",
            "Pelanggaran", "Pasal"
        );

        for ($a = 0; $a < 17; $a++) {
            $sheet->setCellValue($cols[$a] . '1', $val[$a]);
        }

        $baris = 2;
        foreach ($query->get() as $row) {
            if (empty($row->petugas)) {
                $petugas = $row->createdBy->nama_lengkap;
            } else {
                $petugas = $row->petugas->nama;
            }

            $pasal = '';
            $detailpelanggaran = '';
            $datapasal = $row->detailpasal;
            for ($i = 0; $i < count($datapasal); $i++) {
                if ($i == count($datapasal) - 1) {
                    $pasal .= $datapasal[$i]->desk_pasal;
                } else {
                    $pasal .= $datapasal[$i]->desk_pasal . ', ';
                }
            }
            $dp = $row->detailpelanggaran;
            for ($i = 0; $i < count($dp); $i++) {
                if ($i == count($dp) - 1) {
                    $detailpelanggaran .= $dp[$i]->deskripsi;
                } else {
                    $detailpelanggaran .= $dp[$i]->deskripsi . ', ';
                }
            }

            $sheet->setCellValue('A' . $baris, date('d-m-Y H:i:s', strtotime($row->tgl_pelanggaran)));
            $sheet->setCellValue('B' . $baris, $petugas);
            $sheet->setCellValue('C' . $baris, $row->device->nama ?? '');
            $sheet->setCellValue('D' . $baris, $row->no_kendaraan);
            $sheet->setCellValue('E' . $baris, $row->no_uji ?? '');
            $sheet->setCellValue('F' . $baris, $row->nama_pemilik ?? '');
            $sheet->setCellValue('G' . $baris, $row->alamat_pemilik ?? '');
            $sheet->setCellValue('H' . $baris, date('d-m-Y', strtotime($row->tgl_masa_berlaku)) ?? 0);
            $sheet->setCellValue('I' . $baris, $row->jenis_kendaraan ?? '');
            $sheet->setCellValue('J' . $baris, $row->sumbu ?? '');
            $sheet->setCellValue('K' . $baris, $row->jbi_uji ?? 0);
            $sheet->setCellValue('L' . $baris, $row->berat_timbang ?? 0);
            $sheet->setCellValue('M' . $baris, $row->kelebihan_berat ?? 0);
            $sheet->setCellValue('N' . $baris, $row->prosen_lebih ?? 0);
            $sheet->setCellValue('O' . $baris, $row->prosen_lebih ?? 0);
            $sheet->setCellValue('P' . $baris, $detailpelanggaran);
            $sheet->setCellValue('Q' . $baris, $pasal);
            $baris++;
        };

        $writer = new Xlsx($spreadsheet);
        $writer->save('laporan/excel/' . $namaFile . '.xlsx');
        $url = url('/') . '/laporan/excel/' . $namaFile . '.xlsx';
        $res = [
            'nama_file' => $namaFile . '.xlsx',
            'url_file' => $url,
        ];

        return new Resource(true, __('message.SIMPAN_BERHASIL'), $res);
        //}
    }

    public function laporan(Request $request)
    {
        $tgl_dari = $request->tgl_dari ?? date('Y-m-d');
        $tgl_sampai = $request->tgl_sampai ?? date('Y-m-d');
        $kuppkb = $request->kuppkb ?? env('MIX_KODE_UPPKB');
        $jenis_pelanggaran = $request->jenis_pelanggaran;
        $shift = $request->shift;
        $regu = $request->regu;
        $ispdf = $request->ispdf;

        $query = Model::with(['device', 'regu', 'shift', 'petugas', 'detailcapture', 'detailpelanggaran', 'detailpasal', 'createdBy', 'updatedBy', 'deletedBy'])->where('is_active', true)->orderBy('tgl_pelanggaran', 'asc');
        if ($tgl_dari || $tgl_sampai) {
            $query->whereBetween(DB::raw('DATE(tgl_pelanggaran)'), [$tgl_dari, $tgl_sampai]);
        }
        if ($jenis_pelanggaran) {
            $query->whereHas('detailpelanggaran', function ($q) use ($jenis_pelanggaran) {
                $q->where('jenis_pelanggaran_id', $jenis_pelanggaran);
            });
        }
        if ($shift) {
            $query->where('shift_id', $shift);
        }
        if ($regu) {
            $query->where('regu_id', $regu);
        }

        $uppkb = DB::table('jt_lokasi_uppkb')->where('kode', $kuppkb)->first();

        if (date('Y-m-d', strtotime($tgl_dari)) === date('Y-m-d', strtotime($tgl_sampai))) {
            $namaFile = 'laporan_pelanggaran_' . $tgl_dari;
        } else {
            $namaFile = 'laporan_pelanggaran_' . $tgl_dari . '_' . $tgl_sampai;
        }
        
        $uploadFolder = public_path() . '/laporan/qr';
        $urlqr = url('/') . '/laporan/qr/' . $namaFile . '.svg';
        QrCode::generate($urlqr, public_path() . '/laporan/qr/' . $namaFile . '.svg');
        $qrcode_name = $namaFile . '.svg';
        $qrcode_url = url('/') . '/laporan/qr/' . $namaFile . '.svg';

        $data = [
            'pelanggaran' => $query->get(),
            'tgl_dari' => date('d-m-Y', strtotime($tgl_dari)),
            'tgl_sampai' => date('d-m-Y', strtotime($tgl_sampai)),
            'uppkb' => $uppkb->nama,
            'qrurl' => $qrcode_name,
        ];

        $data2 = [
            'pelanggaran' => $query->get(),
            'tgl_dari' => date('d-m-Y', strtotime($tgl_dari)),
            'tgl_sampai' => date('d-m-Y', strtotime($tgl_sampai)),
            'uppkb' => $uppkb->nama,
            'qrurl' => $qrcode_url,
            'logo' => url('/') . '/images/logo.png',
        ];


        if ($ispdf == 0) {
            $contents = view('print_laporan_pelanggaran')->with($data2);
            $response = Response::make($contents, 200);
            $response->header('Content-Type', 'text/plain');
            return $response;
        } else {
            $pdf = PDF::loadView('laporan_pelanggaran', $data);
            $pdf->setPaper('A4', 'landscape');
            //return $pdf->stream('laporan.pdf');
            $pdf->setWarnings(false)->save('laporan/pdf/' . $namaFile . '.pdf');
            $url = url('/') . '/laporan/pdf' . '/' . $namaFile . '.pdf';
            $res = [
                'nama_file' => $namaFile . '.pdf',
                'url_file' => $url,
            ];
            return new Resource(true, __('message.SIMPAN_BERHASIL'), $res);
        }

        //}
    }

    public function exportpdf(Request $request)
    {
        $id = $request->id;
        $kuppkb = $request->kuppkb ?? env('MIX_KODE_UPPKB');
        $title = "Bukti Pelanggaran";
        $query = Model::with(['device', 'regu', 'shift', 'detailcapture', 'detailpelanggaran', 'detailpasal', 'createdBy', 'updatedBy', 'deletedBy'])->where('id', $id);
        $pasal = '';
        $detailpelanggaran = '';
        $head = DB::table('jt_lokasi_uppkb')
            ->join('jt_bptd', 'jt_lokasi_uppkb.bptd_id', '=', 'jt_bptd.id')
            ->select('jt_lokasi_uppkb.*', 'jt_bptd.nama as bptd')
            ->where('jt_lokasi_uppkb.kode', $kuppkb)
            ->get();

        $d = $query->first();

        $tgl = Carbon::parse($d->tgl_pelanggaran)->isoFormat('DD-MM-YYYY');
        $jam = Carbon::parse($d->tgl_pelanggaran)->isoFormat('HH:mm:ss');
        $masa_berlaku = Carbon::parse($d->tgl_masa_berlaku)->isoFormat('DD-MM-YYYY');
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

        $no_kendaraan = $d->no_kendaraan;

        $detailcapture = DetailCapture::where('pelanggaran_id', $d->id)->where('is_plat', false)->get();
        $plat = DetailCapture::where('pelanggaran_id', $d->id)->where('is_plat', true)->get();
        $tgl_capture = Carbon::parse($detailcapture[0]->tgl_capture)->isoFormat('DD-MM-YYYY');

        $kors = DB::table('jt_petugas')->where('kode_uppkb', $kuppkb)->where('is_korsatpel', true)->where('is_active', true)->first();
        $cekppns = DB::table('jt_petugas')->where('kode_uppkb', $kuppkb)->where('regu_id', $d->regu_id)->where('is_ppns', true)->where('is_active', true)->first();

        if ($cekppns) {
            $ppns = $cekppns;
        } else {
            $ppns = DB::table('jt_petugas')->where('kode_uppkb', $kuppkb)->where('is_ppns', true)->first();
        }

        if ($d->petugas_id === 0 || $d->petugas_id === null) {
            $petugas = $d->createdBy->nama_lengkap;
        } else {
            $cekpetugas = DB::table('jt_petugas')->where('id', $d->petugas_id)->first();
            $petugas = $cekpetugas->nama;
        }

        $data = [
            'pelanggaran' => $query->get(),
            'pasal' => $pasal,
            'title' => $title,
            'detail_pelanggaran' => $detailpelanggaran,
            'head' => $head,
            'tgl'  => $tgl,
            'jam'  => $jam,
            'masa_berlaku'  => $masa_berlaku,
            'operator' => $petugas,
            'korsatpel' => $kors->nama,
            'nip' => $kors->nip,
            'ppns' => $ppns->nama,
            'nipppns' => $ppns->nip,
            'detailcapture' => $detailcapture,
            'plat' => $plat,
            'tgl_capture' => $tgl_capture,
            'total' => count($plat),
            'qrurl' => $d->qrcode_url,
            'logo' => url('/') . '/images/logo.png',
            'ttd_url' => $head[0]->ttd_url,
        ];
        //return $data;
        // $pdf = PDF::loadView('cetak_pelanggaran', $data);
        // $pdf->setPaper('A4', 'portrait');
        // return $pdf->stream('export.pdf');

        $namaFile = 'bukti_pelanggaran_' . $no_kendaraan . '_' . $tgl;

        if ($d->is_print) {
            if (file_exists(public_path() . '/print/' . $namaFile . '.pdf')) {
                $contents = view('cetak_pelanggaran')->with($data);
                $response = Response::make($contents, 200);
                $response->header('Content-Type', 'text/plain');
                return $response;

                // $url = url('/') . '/print/' . $namaFile . '.pdf';
                // $res2 = [
                //     'nama_file' => $namaFile . '.pdf',
                //     'url_file' => $url,
                // ];

                // return new Resource(true, 'File Sudah Tersedia', $res2);
            } else {
                $contents = view('cetak_pelanggaran')->with($data);
                $response = Response::make($contents, 200);
                $response->header('Content-Type', 'text/plain');
                return $response;

                // $pdf = PDF::loadView('cetak_pelanggaran', $data);
                // $pdf->setPaper('A4', 'portrait');
                // $pdf->setWarnings(false)->save('print/' . $namaFile . '.pdf');
                $url = url('/') . '/print/' . $namaFile . '.pdf';
                // $res = [
                //     'nama_file' => $namaFile . '.pdf',
                //     'url_file' => $url,
                // ];

                // return new Resource(true, __('message.SIMPAN_BERHASIL'), $res);
            }
        } else {
            $contents = view('cetak_pelanggaran')->with($data);
            $response = Response::make($contents, 200);
            $response->header('Content-Type', 'text/plain');


            // $pdf = PDF::loadView('cetak_pelanggaran', $data);
            // $pdf->setPaper('A4', 'portrait');
            // $pdf->setWarnings(false)->save('print/' . $namaFile . '.pdf');
            $url = url('/') . '/print/' . $namaFile . '.pdf';
            // $res = [
            //     'nama_file' => $namaFile . '.pdf',
            //     'url_file' => $url,
            // ];

            $update = Model::where('id', $id)->update([
                'is_print' => true,
                'print_url' => $url,
            ]);

            if ($update) {
                return $response;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('message.SIMPAN_GAGAL'),
                ], 401);
            }
        }
        // $meta = [
        //     'sort' => $sort,
        //     'sortby' => $sortby,
        //     'page' => $page <= 0 ? 1 : (int) $page,
        //     'limit' => (int) $limit,
        //     'totalData' => (int) $total
        // ];

        //return new Resource(true, __('message.GET_BERHASIL'), $query);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function getKodePelanggaran($kode_uppkb, $tgl_pelanggaran, $no_kendaraan)
    {
        $tgl = date('mY');
        $tahun = Carbon::parse($tgl_pelanggaran)->isoFormat('YYYY');
        $bulan = Carbon::parse($tgl_pelanggaran)->isoFormat('MM');
        Log::debug('Tanggal : ', ['data' => $tgl]);
        Log::debug('Bulan : ', ['data' => $bulan]);
        Log::debug('Tahun : ', ['data' => $tahun]);
        // $sql = Model::where('is_active', true)
        //     ->whereYear('tgl_pelanggaran', $tahun)
        //     ->whereMonth('tgl_pelanggaran', $bulan)
        //     ->get();
        $no_urut = Model::where('is_active', true)
        ->whereYear('tgl_pelanggaran', $tahun)
        ->whereMonth('tgl_pelanggaran', $bulan)
        ->count() + 1;
        
        Log::debug('Cek No Urut Pelanggaran : ', ['data' => $no_urut]);

        $s = str_pad($no_urut, 7, '0', STR_PAD_LEFT);

        // if (count($sql) > 0) {
        //     $no_urut = count($sql) + 1;
        // } else {
        //     $no_urut = 1;
        // }

        // $size = 7;
        // $s = strval($no_urut);
        // while (strlen($s) < $size) {
        //     $s = "0" . $s;
        // }

        $val = rand(1000, 9999);

        $res = 'VR' . $val . '_' . $no_kendaraan . '_' . $kode_uppkb . $s . '_' . $tgl;
        Log::debug('Kode Pelanggaran : ', ['data' => $res]);
        return $res;
    }

    public function getLokasi($kode_uppkb)
    {
        $sql = DB::table('jt_lokasi_uppkb')->where('kode', $kode_uppkb)->first();

        return $sql;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_kendaraan' => 'required|max:30',
        ]);
        Log::debug('Data Request:', ['data' => $request->all()]);

        if ($validator->fails()) {

            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }
        $kd_uppkb = env('MIX_KODE_UPPKB');
        $refNo = $request->input('no_ref');
        Log::debug('Kode UPPKB:', ['kode' => $kd_uppkb]);
        $ambilLokasi = $this->getLokasi($kd_uppkb);
        $bptd_id = $ambilLokasi->bptd_id;
        $lokasi_id = $ambilLokasi->id;

        Log::debug('Lokasi : ', ['data' => $ambilLokasi]);

        $fromArsip = $request->from_archive ?? false;
        $no_kendaraan = $request->input('no_kendaraan');
        $tgl_pelanggaran = $request->input('tgl_pelanggaran') ?? Carbon::now();
        Log::debug('Arsip ? : ', ['data' => $fromArsip]);
        Log::debug('Tanggal Pelanggaran ? : ', ['data' => $tgl_pelanggaran]);
        $kd_pelanggaran = $this->getKodePelanggaran($kd_uppkb, $tgl_pelanggaran, $no_kendaraan);


        $tgl_dari = date('Y-m-d H:i:s', strtotime("-15 minutes", strtotime($request->tgl_capture)));
        $tgl_sampai = date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime($request->tgl_capture)));

        $cek = Model::where('no_kendaraan', $request->no_kendaraan)
            ->where('is_print', false)
            ->whereBetween('tgl_capture', [$tgl_dari, $tgl_sampai]);

        Log::debug('Exist Data Pelanggaran : ', ['data' => $cek]);

        if (count($cek->get()) < 1) {
            Log::debug('Pelaanggaran Tidak Tersedia');
            $head = DB::table('jt_lokasi_uppkb')
                ->where('kode', $kd_uppkb)
                ->first();
            $nmplg = [];
            // dd($request->input('pelanggaran'));
            if ($request->input('pelanggaran')) {
                $plg = JenisPelanggaran::whereIn('id', explode(",", $request->input('pelanggaran')))->get();
                foreach ($plg as $p) {
                    $nmplg[] = $p->nama;
                };
            }
            $uploadFolder = public_path() . '/images/qr_code';
            $url = url('/bukti_pelanggaran/' . $refNo);
            $tglplg = Carbon::parse($request->input('tgl_pelanggaran'))->isoFormat('DD-MM-YYYY HH:mm:ss');
            $isiQr = 'No Kendaraan : ' . $request->input('no_kendaraan') . ' # Tanggal Melanggar : ' . $tglplg . ' # Jenis Kendaraan : ' . $request->input('jenis_kendaraan') . ' # Pelanggaran : ' . implode(", ", $nmplg) . ' # Lokasi UPPKB : ' . $head->nama;
            QrCode::generate($isiQr, public_path() . '/images/qr_code/' . $refNo . '.svg');
            $qrcode_name = $refNo . '.svg';
            $qrcode_url = url('/') . '/images/qr_code/' . $refNo . '.svg';
            $query = Model::create([
                'kd_pelanggaran' => $kd_pelanggaran,
                'tgl_pelanggaran' => $request->input('tgl_pelanggaran'),
                'no_ref' => $refNo,
                'kode_uppkb' => $kd_uppkb,
                'bptd_id' => $bptd_id,
                'regu_id' => $request->input('regu_id'),
                'shift_id' => $request->input('shift_id'),
                'no_kendaraan' => $request->input('no_kendaraan'),
                'no_uji' => $request->input('no_uji'),
                'tgl_uji' => $request->input('tgl_uji'),
                'tgl_masa_berlaku' => $request->input('tgl_masa_berlaku'),
                'nama_pemilik' => $request->input('nama_pemilik'),
                'alamat_pemilik' => $request->input('alamat_pemilik'),
                'jbi_uji' => $request->input('jbi_uji'),
                'mst_uji' => $request->input('mst_uji'),
                'jenis_kendaraan_id' => $request->input('jenis_kendaraan_id'),
                'jenis_kendaraan' => $request->input('jenis_kendaraan'),
                'sumbu_id' => $request->input('sumbu_id'),
                'sumbu' => $request->input('sumbu'),
                'kategori_kepemilikan_id' => $request->input('kategori_kepemilikan_id'),
                'berat_timbang' => $request->input('berat_timbang'),
                'kelebihan_berat' => $request->input('kelebihan_berat'),
                'prosen_lebih' => $request->input('prosen_lebih'),
                'panjang_ukur' => $request->input('panjang_ukur'),
                'panjang_utama' => $request->input('panjang_utama'),
                'panjang_toleransi' => $request->input('panjang_toleransi'),
                'panjang_lebih' => $request->input('panjang_lebih'),
                'lebar_ukur' => $request->input('lebar_ukur'),
                'lebar_utama' => $request->input('lebar_utama'),
                'lebar_toleransi' => $request->input('lebar_toleransi'),
                'lebar_lebih' => $request->input('lebar_lebih'),
                'tinggi_ukur' => $request->input('tinggi_ukur'),
                'tinggi_utama' => $request->input('tinggi_utama'),
                'tinggi_toleransi' => $request->input('tinggi_toleransi'),
                'tinggi_lebih' => $request->input('tinggi_lebih'),
                'foh_ukur' => $request->input('foh_ukur'),
                'foh_utama' => $request->input('foh_utama'),
                'foh_toleransi' => $request->input('foh_toleransi'),
                'foh_lebih' => $request->input('foh_lebih'),
                'roh_ukur' => $request->input('roh_ukur'),
                'roh_utama' => $request->input('roh_utama'),
                'roh_toleransi' => $request->input('roh_toleransi'),
                'roh_lebih' => $request->input('roh_lebih'),
                'device_id' => $request->input('device_id'),
                'lokasi_id' => $lokasi_id,
                'petugas_id' => $request->input('petugas_id'),
                'qrcode_name' => $qrcode_name,
                'qrcode_url' => $qrcode_url,
                'tgl_capture' => $request->input('tgl_capture') ?? date('Y-m-d H:i:s'),
            ]);

            Log::debug('Data hasil Crete:', ['responseData' => $query]);

            $id_cap = $request->input('capture');
            if ($fromArsip) {
                $capture = VerifikasiArchive::whereIn('id', explode(",", $id_cap))->get();
            } else {
                $capture = Capture::whereIn('id', explode(",", $id_cap))->get();
            };
            foreach ($capture as $c) {
                if ($c->img_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $query->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img_name,
                        'img_url' => $c->img_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => $c->is_plat,
                        'device_id' => $c->device_id
                    ]);
                }
                if ($c->img2_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $query->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img2_name,
                        'img_url' => $c->img2_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => $c->is_plat,
                        'device_id' => $c->device_id
                    ]);
                }
                if ($c->img3_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $query->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img3_name,
                        'img_url' => $c->img3_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => $c->is_plat,
                        'device_id' => $c->device_id
                    ]);
                }
                if ($c->img4_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $query->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img4_name,
                        'img_url' => $c->img4_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => $c->is_plat,
                        'device_id' => $c->device_id
                    ]);
                }
                if ($c->img_plat_depan_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $query->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img_plat_depan_name,
                        'img_url' => $c->img_plat_depan_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => true,
                        'device_id' => $c->device_id
                    ]);
                }
                if ($c->img_plat_belakang_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $query->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img_plat_belakang_name,
                        'img_url' => $c->img_plat_belakang_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => true,
                        'device_id' => $c->device_id
                    ]);
                }
            }

            if ($fromArsip) {
                VerifikasiArchive::whereIn('id', explode(",", $id_cap))->forceDelete();
            } else {
                Capture::whereIn('id', explode(",", $id_cap))->forceDelete();
            };

            $id_plg = $request->input('pelanggaran');
            if ($id_plg) {
                $pelanggaran = JenisPelanggaran::whereIn('id', explode(",", $id_plg))->get();
                foreach ($pelanggaran as $p) {
                    DetailPelanggaran::create([
                        'pelanggaran_id' => $query->id,
                        'jenis_pelanggaran_id' => $p->id,
                        'kode_pelanggaran' => $p->kode,
                        'deskripsi' => $p->nama,
                        'is_active' => true
                    ]);
                }
            }

            $id_pasal = $request->input('pasal');
            if ($id_pasal) {
                $pasal = Pasal::whereIn('id', explode(",", $id_pasal))->get();
                foreach ($pasal as $ps) {
                    DetailPasal::create([
                        'pelanggaran_id' => $query->id,
                        'pasal_id' => $ps->id,
                        'desk_pasal' => $ps->pasal,
                        'is_active' => true
                    ]);
                }
            }

            $sinkron_pusat = env('IS_SINKRON_VERIFIKASI');

            $meta = [];

            if ($sinkron_pusat === 1 || $sinkron_pusat === '1') {
                $dataSink = Model::with(['detailcapture', 'detailpelanggaran', 'detailpasal'])->where('id', $query->id)->first();
                $urlPusat = env('MIX_API_URL_JTO_PUSAT'); //'https://jto.kemenhub.go.id/api-lhr/v2'; // env('MIX_API_URL_JTO_PUSAT');
                $timeout = 10;
                $httpCode = null;
                $curlError = null;
                $responseData = null;

                try {
                    $lokasi = storage_path('app/app_config.json');
                    $dataApp = file_get_contents($lokasi);
                    $data = json_decode($dataApp);
                    
                    // Inisialisasi CURL
                    $ch = curl_init();
                    $url = $urlPusat . '/v2pv/vrpelanggaran/create';
                    $postData = json_encode($dataSink);
                    
                    // Set CURL options
                    curl_setopt_array($ch, [
                        CURLOPT_URL => $url,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $postData,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => $timeout,
                        CURLOPT_CONNECTTIMEOUT => $timeout,
                        CURLOPT_HTTPHEADER => [
                            'Authorization: Bearer ' . $data->accessToken,
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($postData)
                        ],
                        CURLOPT_SSL_VERIFYPEER => true,
                        CURLOPT_SSL_VERIFYHOST => 2,
                    ]);

                    // Eksekusi request
                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $curlError = curl_error($ch);
                    $curlErrno = curl_errno($ch);
                    
                    curl_close($ch);

                    // Cek error CURL
                    if ($curlErrno !== CURLE_OK) {
                        throw new \Exception('CURL Error: ' . $curlError);
                    }

                    // Cek HTTP status code
                    if ($httpCode < 200 || $httpCode >= 300) {
                        throw new \Exception('HTTP Error: ' . $httpCode . ' - ' . $responseData);
                    }

                    Log::debug('Data hasil API:', [
                        'httpCode' => $httpCode,
                        'responseData' => $responseData
                    ]);

                    $queryUP = Model::where('id', $query->id)->update([
                        'sync_to_pusat'     => true,
                    ]);

                    if ($queryUP) {
                        $responseData = json_decode($response, true);
                        $statusSink = 'SINKRONISASI KE PUSAT & UPDATE BERAHSIL';
                    } else {
                        $responseData = json_decode($response, true);
                        $statusSink = 'SINKRONISASI KE PUSAT BERAHSIL & UPDATE GAGAL';
                    }

                } catch (\Exception $e) {
                    // Menangani kesalahan jika terjadi
                    $responseData = [
                        'error' => true,
                        'message' => $e->getMessage(),
                        'httpCode' => $httpCode,
                        'curlError' => $curlError
                    ];
                    Log::error('Error sinkronisasi ke pusat:', [
                        'error' => $e->getMessage(),
                        'httpCode' => $httpCode,
                        'curlError' => $curlError,
                        'dataSink' => $dataSink
                    ]);
                }

                $meta = [
                    'status' => $statusSink,
                    'response' => $responseData,
                    'statusCode' => $httpCode,
                ];
            }

            $res = Model::with(['device', 'regu', 'shift', 'petugas', 'detailcapture', 'detailpelanggaran', 'detailpasal', 'createdBy', 'updatedBy', 'deletedBy'])->where('id', $query->id)->first();

            return new Resource(true, __('message.SIMPAN_BERHASIL'), $res, $meta);
        } else {
            Log::debug('Pelaanggaran Tersedia');
            $val = $cek->first();
            $id_plg = $val->id;

            $id_cap = $request->input('capture');
            if ($fromArsip) {
                $capture = VerifikasiArchive::whereIn('id', explode(",", $id_cap))->get();
            } else {
                $capture = Capture::whereIn('id', explode(",", $id_cap))->get();
            };
            foreach ($capture as $c) {
                if ($c->img_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $val->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img_name,
                        'img_url' => $c->img_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => false
                    ]);
                }
                if ($c->img2_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $val->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img2_name,
                        'img_url' => $c->img2_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => false
                    ]);
                }
                if ($c->img3_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $val->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img3_name,
                        'img_url' => $c->img3_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => false
                    ]);
                }
                if ($c->img4_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $val->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img4_name,
                        'img_url' => $c->img4_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => false
                    ]);
                }
                if ($c->img_plat_depan_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $val->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img_plat_depan_name,
                        'img_url' => $c->img_plat_depan_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => true
                    ]);
                }
                if ($c->img_plat_belakang_name) {
                    DetailCapture::create([
                        'pelanggaran_id' => $val->id,
                        'jt_vr_data_id' => $c->id,
                        'img_name' => $c->img_plat_belakang_name,
                        'img_url' => $c->img_plat_belakang_url,
                        'tgl_capture' => $c->tgl_capture,
                        'is_active' => true,
                        'is_plat' => true
                    ]);
                }
            }

            if ($fromArsip) {
                VerifikasiArchive::whereIn('id', explode(",", $id_cap))->forceDelete();
            } else {
                Capture::whereIn('id', explode(",", $id_cap))->forceDelete();
            };

            $res = Model::with(['device', 'regu', 'shift', 'petugas', 'detailcapture', 'detailpelanggaran', 'detailpasal', 'createdBy', 'updatedBy', 'deletedBy'])->where('id', $val->id)->first();

            return new Resource(true, __('message.SIMPAN_BERHASIL'), $res);
        }
    }

    public function archiveArr(Request $request)
    {
        $ids = $request->input('id');
        $arr = Model::with(['detailcapture'])->whereIn('id', explode(",", $ids))->get();
        foreach ($arr as $d) {
            $query = Archive::create([
                'tgl_archive' => $d->tgl_pelanggaran,
                'no_ref' => $d->no_ref,
                'kode_uppkb' => $d->kode_uppkb,
                'regu_id' => $d->regu_id,
                'shift_id' => $d->shift_id,
                'no_kendaraan' => $d->no_kendaraan,
                'no_uji' => $d->no_uji,
                'tgl_uji' => $d->tgl_uji,
                'tgl_masa_berlaku' => $d->tgl_masa_berlaku,
                'nama_pemilik' => $d->nama_pemilik,
                'alamat_pemilik' => $d->alamat_pemilik,
                'jbi_uji' => $d->jbi_uji,
                'mst_uji' => $d->mst_uji,
                'jenis_kendaraan_id' => $d->jenis_kendaraan_id,
                'jenis_kendaraan' => $d->jenis_kendaraan,
                'sumbu_id' => $d->sumbu_id,
                'sumbu' => $d->sumbu,
                'kategori_kepemilikan_id' => $d->kategori_kepemilikan_id,
                'berat_timbang' => $d->berat_timbang,
                'kelebihan_berat' => $d->kelebihan_berat,
                'prosen_lebih' => $d->prosen_lebih,
                'panjang_ukur' => $d->panjang_ukur,
                'panjang_utama' => $d->panjang_utama,
                'panjang_toleransi' => $d->panjang_toleransi,
                'panjang_lebih' => $d->panjang_lebih,
                'lebar_ukur' => $d->lebar_ukur,
                'lebar_utama' => $d->lebar_utama,
                'lebar_toleransi' => $d->lebar_toleransi,
                'lebar_lebih' => $d->lebar_lebih,
                'tinggi_ukur' => $d->tinggi_ukur,
                'tinggi_utama' => $d->tinggi_utama,
                'tinggi_toleransi' => $d->tinggi_toleransi,
                'tinggi_lebih' => $d->tinggi_lebih,
                'foh_ukur' => $d->foh_ukur,
                'foh_utama' => $d->foh_utama,
                'foh_toleransi' => $d->foh_toleransi,
                'foh_lebih' => $d->foh_lebih,
                'roh_ukur' => $d->roh_ukur,
                'roh_utama' => $d->roh_utama,
                'roh_toleransi' => $d->roh_toleransi,
                'roh_lebih' => $d->roh_lebih,
                'device_id' => $d->device_id,
                'petugas_id' => $d->petugas_id,
                'qrcode_name' => $d->qrcode_name,
                'qrcode_url' => $d->qrcode_url,
                'tgl_capture' => $d->tgl_capture,
                'device_id' => $d->device_id,
                'petugas_id' => $d->petugas_id,
                'keterangan' => $request->input('keterangan'),
                'keterangan_id' => '1',
            ]);

            $id_cap = [];
            foreach ($d->detailcapture as $dc) {
                $id_cap[] = $dc->id;
            }
            DetailCapture::whereIn('id', explode(",", implode(",", $id_cap)))->update([
                'pelanggaran_id' => $query->id,
            ]);

            Model::where('id', $d->id)->update([
                'is_active' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Simpan Data Archive Berhasil',
            'data' => $query
        ], 200);
    }

    public function archive(Request $request)
    {
        $id_cap = $request->input('capture');
        $capture = Capture::whereIn('id', explode(",", $id_cap))->get();
        foreach ($capture as $c) {
            $query = VerifikasiArchive::create([
                'tgl_capture' => $c->tgl_capture,
                'no_kendaraan' => $c->no_kendaraan,
                'device_id' => $c->device_id,
                'berat_timbang' => $c->berat_timbang,
                'panjang_ukur' => $c->panjang_ukur ?? 0,
                'lebar_ukur' => $c->lebar_ukur ?? 0,
                'tinggi_ukur' => $c->tinggi_ukur ?? 0,
                'foh_ukur' => $c->foh_ukur ?? 0,
                'roh_ukur' => $c->roh_ukur ?? 0,
                'img_name' => $c->img_name,
                'img2_name' => $c->img2_name,
                'img3_name' => $c->img3_name,
                'img4_name' => $c->img4_name,
                'img_plat_depan_name' => $c->img_plat_depan_name,
                'img_plat_belakang_name' => $c->img_plat_belakang_name,
                'img_url' => $c->img_url,
                'img2_url' => $c->img2_url,
                'img3_url' => $c->img3_url,
                'img4_url' => $c->img4_url,
                'img_plat_depan_url' => $c->img_plat_depan_url,
                'img_plat_belakang_url' => $c->img_plat_belakang_url,
                'is_plat' => $c->is_plat,
                'kd_referensi' => $c->kd_referensi,
                'id_referensi' => $c->id_referensi,
                'keterangan' => $request->input('keterangan'),
                'keterangan_id' => $request->input('keterangan_id'),
                'jt_vr_data_id' => $c->id,
            ]);
        }

        Capture::whereIn('id', explode(",", $id_cap))->forceDelete();

        //$res = Archive::with(['device', 'regu', 'shift', 'petugas', 'detailcapture', 'createdBy', 'updatedBy', 'deletedBy'])->where('id', $query->id)->first();

        return new Resource(true, __('message.SIMPAN_BERHASIL'), $capture);
    }

    public function createDetail(Request $request)
    {
        $input = $request->all();
        //$capture = $input['capture'];
        $detailCapture = $input['detailCapture'];
        $detailPelanggaran = $input['detailPelanggaran'];
        $detailPasal = $input['detailPasal'];

        // for ($i = 0; $i < count($capture); $i++) {
        //     Capture::where('id', $capture[$i])->update(array('is_verifikasi' => true));
        // }

        // foreach ($capture as $c) {
        //     Capture::where('id', $c)->update(array('is_verifikasi' => true));
        // };

        if (count($detailCapture) > 0) {
            foreach ($detailCapture as $dc) {
                DetailCapture::create($dc);
            };
        }

        if (count($detailPelanggaran) > 0) {
            foreach ($detailPelanggaran as $dp) {
                DetailPelanggaran::create($dp);
            };
        }

        if (count($detailPasal) > 0) {
            foreach ($detailPasal as $ds) {
                DetailPasal::create($ds);
            };
        }

        return new Resource(true, __('message.SIMPAN_BERHASIL'), $input);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Model  $query
     * @return \Illuminate\Http\Response
     */
    public function show(Model $pelanggaran)
    {
        return new Resource(true, __('message.GET_BERHASIL'), $pelanggaran);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Model  $query
     * @return \Illuminate\Http\Response
     */
    public function edit(Model $query)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Model  $query
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $pelanggaran)
    {
        $validator = Validator::make($request->all(), [
            'no_kendaraan' => 'required|max:30',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $pelanggaran->update($request->all());
        return new Resource(true, __('message.SIMPAN_BERHASIL'), $pelanggaran);
    }

    public function updateActive(Request $request)
    {
        $ids = $request->id;
        $query = Model::whereIn('id', explode(",", $ids))->update([
            'is_active'     => $request->input('is_active') ?? true,
        ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'message' => __('message.SIMPAN_BERHASIL'),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('message.SIMPAN_GAGAL'),
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Model  $query
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $pelanggaran)
    {
        $query = $pelanggaran->delete();

        if ($query) {
            return response()->json([
                "success" => true,
                "message" => __('message.HAPUS_BERHASIL'),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('message.HAPUS_GAGAL'),
            ], 401);
        }
    }

    public function trushArr(Request $request)
    {
        $ids = $request->id;
        $query = Model::whereIn('id', explode(",", $ids))->delete();

        if ($query) {
            return response()->json([
                "success" => true,
                "message" => __('message.HAPUS_BERHASIL'),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('message.HAPUS_GAGAL'),
            ], 401);
        }
    }

    public function delete(Request $request)
    {
        $ids = $request->id;

        $query = Model::withTrashed()->whereIn('id', explode(",", $ids))->forceDelete();

        if ($query) {
            return response()->json([
                'success' => true,
                'message' => __('message.HAPUS_HARD_BERHASIL'),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('message.HAPUS_HARD_GAGAL'),
            ], 401);
        }
    }

    public function restore($id)
    {
        $query = Model::withTrashed()->find($id)->restore();

        if ($query) {
            return response()->json([
                'success' => true,
                'message' => __('message.RESTORE_BERHASIL'),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('message.RESTORE_GAGAL'),
            ], 401);
        }
    }

    public function restoreAll()
    {
        $query = Model::onlyTrashed()->restore();
        if ($query) {
            return response()->json([
                'success' => true,
                'message' => __('message.RESTORE_BERHASIL'),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('message.RESTORE_GAGAL'),
            ], 401);
        }
    }
}
