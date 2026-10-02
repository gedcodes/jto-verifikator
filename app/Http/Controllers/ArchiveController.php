<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArchiveResource as Resource;
use App\Models\Archive;
use App\Models\Capture;
use App\Models\DetailCapture;
use App\Models\DetailPasal;
use App\Models\DetailPelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\Pasal;
use App\Models\Archive as Model;
use App\Models\VerifikasiArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class ArchiveController extends Controller
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
        $shift = $request->shift;
        $regu = $request->regu;
        //$total = Model::count();
        $query = Model::with(['device', 'regu', 'shift', 'petugas', 'createdBy', 'updatedBy', 'deletedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit);

        if ($tgl_dari || $tgl_sampai) {
            $query->whereBetween(DB::raw('DATE(tgl_archive)'), [$tgl_dari, $tgl_sampai]);
        }
        if ($no_kendaraan) {
            $query->where('no_kendaraan', $no_kendaraan);
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
            'totalData' => count($query->get()),
        ];

        $data = $query->get();
        $i = 0;

        foreach ($data as $row) {
            $qry = DetailCapture::where('pelanggaran_id', $row->id)->first();
            if ($qry) {
                $capture = $qry->img_url;
            } else {
                $capture = url('/') . '/images/noimage.png';
            }
            $data[$i]['gambar'] = $capture;
            $i++;
        }


        //return collection of posts as a resource
        return new Resource(true, __('message.GET_BERHASIL'), $data, $meta);
        // return Resource::collection($query);
    }

    public function arsipverifikasi(Request $request)
    {
        $sort = $request->sort ?? 'tgl_capture';
        $sortby = $request->sortby ?? 'asc';
        $page = $request->page - 1 ?? 0;
        $limit = $request->limit ?? 25;
        $tgl_dari = $request->tgl_dari ?? date('Y-m-d');
        $tgl_sampai = $request->tgl_sampai ?? date('Y-m-d');

        $device = $request->device;

        if ($device) {
            $total = VerifikasiArchive::where('is_active', true)->where('is_verifikasi', false)->whereBetween(DB::raw('DATE(tgl_capture)'), [$tgl_dari, $tgl_sampai])->where('is_plat', false)->where('device_id', $device)->get();
            $query = VerifikasiArchive::with(['device', 'createdBy', 'updatedBy', 'deletedBy'])->where('is_active', true)->where('is_verifikasi', false)->whereBetween(DB::raw('DATE(tgl_capture)'), [$tgl_dari, $tgl_sampai])->where('device_id', $device)->orderBy($sort, $sortby)->offset($page)->limit($limit)->get();
        } else {
            $total = VerifikasiArchive::where('is_active', true)->where('is_verifikasi', false)->whereBetween(DB::raw('DATE(tgl_capture)'), [$tgl_dari, $tgl_sampai])->where('is_plat', false)->get();
            $query = VerifikasiArchive::with(['device', 'createdBy', 'updatedBy', 'deletedBy'])->where('is_active', true)->where('is_verifikasi', false)->whereBetween(DB::raw('DATE(tgl_capture)'), [$tgl_dari, $tgl_sampai])->orderBy($sort, $sortby)->offset($page)->limit($limit)->get();
        }
        // dd($query);

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'total' => $total->count(),
            'totalData' => $query->count(),
            'totalPages' => ceil($total->count() / $limit),
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $query, $meta);
    }

    public function selected(Request $request)
    {
        $sort = $request->sort ?? 'tgl_capture';
        $sortby = $request->sortby ?? 'asc';
        $tanggal = $request->tanggal ?? date('Y-m-d H:i:s');
        $nokend = $request->nokend;
        $id = $request->id;
        $tgl_dari = date('Y-m-d H:i:s', strtotime("-1 minutes", strtotime($tanggal)));
        $tgl_sampai = date('Y-m-d H:i:s', strtotime("+1 minutes", strtotime($tanggal)));
        $data = [];
        $query = VerifikasiArchive::with(['device', 'createdBy', 'updatedBy', 'deletedBy'])
            ->where('is_active', true)
            ->whereDate('tgl_capture', $tanggal)
            ->where('no_kendaraan', $nokend)
            ->where('keterangan_id', '1')
            ->orderBy($sort, $sortby)
            ->get();

        $qry = VerifikasiArchive::where('id', $id)->first();

        $imageHash = new ImageHash(new DifferenceHash());

        $data = (object) $query;
        $img1 = $qry->img_url; //public_path() . '/images/capture/' . Carbon::parse($tanggal)->isoFormat('DD-MM-YYYY') . '/' . $qry->img_name;
        if (file_exists($img1)) {
            $hash1 = $imageHash->hash($img1);
            $raw = VerifikasiArchive::with(['device', 'createdBy', 'updatedBy', 'deletedBy'])
                ->where('is_active', true)
                ->where('is_verifikasi', false)
                ->where('keterangan_id', '1')
                ->whereBetween('tgl_capture', [$tgl_dari, $tgl_sampai])
                ->orderBy('tgl_capture', 'asc')
                ->get();

            foreach ($raw as $v) {
                if ($v->id !== $id) {
                    $img2 = $v->img_url; //public_path() . '/images/capture/' . Carbon::parse($tgl_dari)->isoFormat('DD-MM-YYYY') . '/' . $v->img_name;
                    if (file_exists($img2)) {
                        $hash2 = $imageHash->hash($img2);
                        $distance = $hash1->distance($hash2);
                        if ($distance < 1) {
                            $check = $data->doesntContain('id', $v->id);
                            if ($check) {
                                $data[] = (object) $v;
                            }
                        }
                    }
                }
            }
        }

        if (isset($qry->img2_name)) {
            $img1 = $qry->img2_url; //public_path() . '/images/capture/' . Carbon::parse($tanggal)->isoFormat('DD-MM-YYYY') . '/' . $qry->img2_name;
            if (file_exists($img1)) {
                $hash1 = $imageHash->hash($img1);
                $raw = VerifikasiArchive::with(['device', 'createdBy', 'updatedBy', 'deletedBy'])
                    ->where('is_active', true)
                    ->where('is_verifikasi', false)
                    ->where('keterangan_id', '1')
                    ->whereBetween('tgl_capture', [$tgl_dari, $tgl_sampai])
                    ->orderBy('tgl_capture', 'asc')
                    ->get();

                foreach ($raw as $v) {
                    if ($v->id !== $id) {
                        $img2 = $v->img_url; //public_path() . '/images/capture/' . Carbon::parse($tgl_dari)->isoFormat('DD-MM-YYYY') . '/' . $v->img_name;
                        if (file_exists($img2)) {
                            $hash2 = $imageHash->hash($img2);
                            $distance = $hash1->distance($hash2);
                            if ($distance < 1) {
                                $check = $data->doesntContain('id', $v->id);
                                if ($check) {
                                    $data[] = (object) $v;
                                }
                            }
                        }
                    }
                }
            }
        }

        if (isset($qry->img3_name)) {
            $img1 = $qry->img3_url; //public_path() . '/images/capture/' . Carbon::parse($tanggal)->isoFormat('DD-MM-YYYY') . '/' . $qry->img3_name;
            if (file_exists($img1)) {
                $hash1 = $imageHash->hash($img1);
                $raw = VerifikasiArchive::with(['device', 'createdBy', 'updatedBy', 'deletedBy'])
                    ->where('is_active', true)
                    ->where('is_verifikasi', false)
                    ->where('keterangan_id', '1')
                    ->whereBetween('tgl_capture', [$tgl_dari, $tgl_sampai])
                    ->orderBy('tgl_capture', 'asc')
                    ->get();

                foreach ($raw as $v) {
                    if ($v->id !== $id) {
                        $img2 = $v->img_url; //public_path() . '/images/capture/' . Carbon::parse($tgl_dari)->isoFormat('DD-MM-YYYY') . '/' . $v->img_name;
                        if (file_exists($img2)) {
                            $hash2 = $imageHash->hash($img2);
                            $distance = $hash1->distance($hash2);
                            if ($distance < 1) {
                                $check = $data->doesntContain('id', $v->id);
                                if ($check) {
                                    $data[] = (object) $v;
                                }
                            }
                        }
                    }
                }
            }
        }

        if (isset($qry->img4_name)) {
            $img1 = $qry->img4_url; //public_path() . '/images/capture/' . Carbon::parse($tanggal)->isoFormat('DD-MM-YYYY') . '/' . $qry->img4_name;
            if (file_exists($img1)) {
                $hash1 = $imageHash->hash($img1);
                $raw = VerifikasiArchive::with(['device', 'createdBy', 'updatedBy', 'deletedBy'])
                    ->where('is_active', true)
                    ->where('is_verifikasi', false)
                    ->where('keterangan_id', '1')
                    ->whereBetween('tgl_capture', [$tgl_dari, $tgl_sampai])
                    ->orderBy('tgl_capture', 'asc')
                    ->get();

                foreach ($raw as $v) {
                    if ($v->id !== $id) {
                        $img2 = $v->img_url; //public_path() . '/images/capture/' . Carbon::parse($tgl_dari)->isoFormat('DD-MM-YYYY') . '/' . $v->img_name;
                        if (file_exists($img2)) {
                            $hash2 = $imageHash->hash($img2);
                            $distance = $hash1->distance($hash2);
                            if ($distance < 1) {
                                $check = $data->doesntContain('id', $v->id);
                                if ($check) {
                                    $data[] = (object) $v;
                                }
                            }
                        }
                    }
                }
            }
        }

        $meta = [];
        //echo "<img src='$qry->img_url'>";
        return new Resource(true, __('message.GET_BERHASIL'), $data, $meta);
    }
}
