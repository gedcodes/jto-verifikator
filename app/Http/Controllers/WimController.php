<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\WimResource as Resource;
use App\Models\Wim as Model;
use Carbon\Carbon;
// use App\Models\Kementerian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Hash;
use Jenssegers\ImageHash\Implementations\AverageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Jenssegers\ImageHash\Implementations\PerceptualHash;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WimController extends Controller
{

    private $imageHash;

    const HASH_DISTANCE = 64;

    public function __construct($algorithm = 'PerceptualHash')
    {
        if ($algorithm == 'AverageHash') {
            $this->imageHash = new ImageHash(new AverageHash());
        } elseif ($algorithm == 'PerceptualHash') {
            $this->imageHash = new ImageHash(new PerceptualHash());
        } else {
            $this->imageHash = new ImageHash(new DifferenceHash());
        }
    }

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
        $tgl_capture = $request->tgl_capture;
        $total = Model::count();
        if ($tgl_capture) {
            $query = Model::whereDate('tgl_penimbangan', $tgl_capture)->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
        } else {
            $query = Model::orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
        }

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => (int) $total
        ];
        //return collection of posts as a resource
        return new Resource(true, __('message.GET_BERHASIL'), $query, $meta);
        // return Resource::collection($query);
    }

    public function active(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $sortby = $request->sortby ?? 'desc';
        $page = $request->page - 1 ?? 0;
        $limit = $request->limit ?? 25;
        $tgl_capture = $request->tgl_capture;
        // $tanggal = $request->tanggal ?? date('Y-m-d');
        // $tanggalawal = $tanggal . ' 00:00:01';
        // $tanggalakhir = $tanggal . ' 23:59:59';
        $total = Model::count();

        if ($tgl_capture) {
            $query = Model::where('is_active', true)->whereDate('tgl_penimbangan', $tgl_capture)->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
        } else {
            $query = Model::where('is_active', true)->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
        }

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => (int) $total
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $query, $meta);
    }

    public function trush(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $sortby = $request->sortby ?? 'desc';
        $page = $request->page - 1 ?? 0;
        $limit = $request->limit ?? 25;
        $total = Model::count();

        $query = Model::onlyTrashed()->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => (int) $total
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $query, $meta);
    }

    public function sink_antrian(Request $request)
    {
        $tgl_dari = $request->tgl_dari ?? Carbon::today()->toDateString();
        $tgl_sampai = $request->tgl_sampai ?? Carbon::today()->toDateString();
        $interval = (int) $request->interval;
        $jam_dari = Carbon::parse($request->jam_dari)->toTimeString();
        $jam_sampai = Carbon::parse($request->jam_sampai)->toTimeString();

        $sink = DB::table('jt_penimbangan')
            ->where('is_transaksi', 0)
            ->where('device_id', 3)
            ->where('is_verifikasi', false);

        if ($interval) {
            if ($interval == 1) {
                // dd($jam_dari);
                $sink->whereDate('tgl_antrian', $tgl_dari)
                    ->whereBetween(DB::raw('tgl_antrian::time'), [$jam_dari, $jam_sampai]);
            } else {
                $sink->whereBetween(DB::raw('DATE(tgl_antrian)'), [$tgl_dari, $tgl_sampai]);
            }
        } else {
            $sink->whereBetween(DB::raw('DATE(tgl_antrian)'), [$tgl_dari, $tgl_sampai]);
        }

        $data = [];
        $jto = $sink->get();

        foreach ($jto as $s) {
            $ins = Model::create([
                'tgl_capture' => $s->tgl_antrian,
                'no_kendaraan' => $s->no_kendaraan,
                'device_id' => 1,
                'berat_timbang' => $s->wim_berat,
                'panjang_ukur' => $s->wim_panjang,
                'lebar_ukur' => $s->wim_lebar,
                'tinggi_ukur' => $s->wim_tinggi,
                'foh_ukur' => $s->wim_foh,
                'roh_ukur' => $s->wim_roh,
                'is_verifikasi' => false,
                'is_active' => true,
                'img_name' => $s->foto_depan,
                'img_plat_depan_name' => $s->plate_no_img_name ?: null,
                'img_url' => $s->foto_depan_url,
                'img_plat_depan_url' => $s->plate_no_img_url ?: null,
                'kd_referensi' => '1',
                'id_referensi' => $s->id
            ]);

            DB::table('jt_penimbangan')->where('id', $s->id)->update([
                'is_verifikasi' => true
            ]);

            array_push($data, $ins);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sinkron data WIM berhasil',
            'data' => $data
        ], 200);
    }

    public function page(Request $request)
    {
        $sort = $request->input('sort', 'tgl_penimbangan');
        $sortby = $request->sortby ?? 'desc';
        $page = (int) $request->page - 1 ?? 0;
        $limit = $request->limit ?? 25;
        $tgl_dari = $request->tgl_dari ?? Carbon::today()->toDateString();
        $tgl_sampai = $request->tgl_sampai ?? Carbon::today()->toDateString();
        // $device = (int) $request->device;
        $interval = (int) $request->interval;
        $jam_dari = Carbon::parse($request->jam_dari)->toTimeString();
        $jam_sampai = Carbon::parse($request->jam_sampai)->toTimeString();

        $jto = DB::table('jt_penimbangan')
            ->where(function ($query) {
                $query->where('is_transaksi', 0)
                    ->orWhere('is_transaksi', 2);
            })
            ->where('device_id', 3)
            ->where('is_verifikasi', false);

        $qry = Model::where(function ($query) {
            $query->where('is_transaksi', 0)
                ->orWhere('is_transaksi', 2);
        })
            ->where('device_id', 3)
            ->orderBy($sort, $sortby)
            ->offset($page)
            ->limit($limit);

        $ttl =
            Model::where('is_transaksi', 2)
            ->where('device_id', 3);

        if ($interval) {
            if ($interval == 1) {
                // dd($jam_dari);
                $jto->whereDate('tgl_antrian', $tgl_dari)
                    ->whereBetween(DB::raw('tgl_antrian::time'), [$jam_dari, $jam_sampai]);
                $qry->whereDate('tgl_penimbangan', $tgl_dari)
                    ->whereBetween(DB::raw('tgl_penimbangan::time'), [$jam_dari, $jam_sampai]);
                $ttl->whereDate('tgl_penimbangan', $tgl_dari)
                    ->whereBetween(DB::raw('tgl_penimbangan::time'), [$jam_dari, $jam_sampai]);
            } else {
                $jto->whereBetween(DB::raw('DATE(tgl_antrian)'), [$tgl_dari, $tgl_sampai]);
                $qry->whereBetween(DB::raw('DATE(tgl_penimbangan)'), [$tgl_dari, $tgl_sampai]);
                $ttl->whereBetween(DB::raw('DATE(tgl_penimbangan)'), [$tgl_dari, $tgl_sampai]);
            }
        } else {
            $jto->whereBetween(DB::raw('DATE(tgl_antrian)'), [$tgl_dari, $tgl_sampai]);
            $qry->whereBetween(DB::raw('DATE(tgl_penimbangan)'), [$tgl_dari, $tgl_sampai]);
            $ttl->whereBetween(DB::raw('DATE(tgl_penimbangan)'), [$tgl_dari, $tgl_sampai]);
        }
        // if ($device) {
        //     $ttl->where('device_id', $device);
        //     $qry->where('device_id', $device);
        // }

        $query = $qry->get();
        $total = $ttl->count('id');

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'total' => $total,
            'totalData' => $query->count('id'),
            'totalPages' => ceil($total / $limit),
            'totalAntrian' => $jto->count(),
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $query, $meta);
    }

    public function compareImages($img1, $img2)
    {
        $headers1 = get_headers($img1);
        $headers2 = get_headers($img2);
        if ($headers1[0] == 'HTTP/1.1 404 Not Found' || $headers2[0] == 'HTTP/1.1 404 Not Found') {
            return 0;
        }

        $hash1 = $this->imageHash->hash($img1);
        $hash2 = $this->imageHash->hash($img2);

        $distance = $hash1->distance($hash2);

        $similarity = (1 - $distance / self::HASH_DISTANCE) * 100;
        return (int) $similarity;
    }

    public function selected(Request $request)
    {
        $sort = $request->sort ?? 'tgl_penimbangan';
        $sortby = $request->sortby ?? 'asc';
        $tanggal = $request->tanggal ?? date('Y-m-d H:i:s');
        $nokend = $request->nokend ?? '';
        $id = $request->id;
        $tgl_dari = date('Y-m-d H:i:s', strtotime("-1 minutes", strtotime($tanggal)));
        $tgl_sampai = date('Y-m-d H:i:s', strtotime("+1 minutes", strtotime($tanggal)));

        $query = Model::where('is_transaksi', 2)
            ->where('device_id', 3)
            ->orderBy($sort, $sortby)
            ->where(function ($q) use ($nokend, $id, $tanggal) {
                if ($nokend === '') {
                    $q->where('id', $id);
                } else {
                    $q->whereDate('tgl_penimbangan', $tanggal)
                        ->where('no_kendaraan', $nokend);
                }
            });

        $data = $query->get();
        $img1 = $data->where('id', $id)->first()->foto_depan_url;
        $simy = [];

        if ($img1) {
            $raw = Model::where('is_transaksi', 2)
                ->where('device_id', 3)
                ->whereBetween('tgl_penimbangan', [$tgl_dari, $tgl_sampai])
                ->orderBy('tgl_penimbangan', 'asc')
                ->get();

            foreach ($raw as $v) {
                if ($v->id === $id) {
                    continue;
                }

                $img2 = $v->foto_depan_url;
                if (!$img2) {
                    continue;
                }

                $distance = $this->compareImages($img1, $img2);
                $simy[] = $distance;

                $check = $data->where('id', $v->id)->isEmpty();
                if ($distance >= 95 && $check) {
                    $data->push($v);
                } elseif ($distance >= 85 && $check) {
                    if ($nokend === '' || is_null($v->no_kendaraan) || $v->no_kendaraan === $nokend) {
                        $data->push($v);
                    }
                }
            }
        }

        return new Resource(true, __('message.GET_BERHASIL'), $data, $simy);
    }

    public function sinkronWim(Request $request)
    {
        # code...
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'device_id'              => 'required',
            'img1_file'              => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'img2_file'              => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'img3_file'              => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'img4_file'              => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'img_plat_depan_file'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'img_plat_belakang_file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);

        if ($validator->fails()) {

            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }
        $img1 = $request->file('img1_file');
        $img2 = $request->file('img2_file');
        $img3 = $request->file('img3_file');
        $img4 = $request->file('img4_file');
        $img_plat_depan = $request->file('img_plat_depan_file');
        $img_plat_belakang = $request->file('img_plat_belakang_file');
        $img1Url = $request->img1_url;
        $img2Url = $request->img2_url;
        $img3Url = $request->img3_url;
        $img4Url = $request->img4_url;
        $img_plat_depanUrl = $request->img_plat_depan_url;
        $img_plat_belakangUrl = $request->img_plat_belakang_url;
        $img_plat_depanBase = $request->img_plat_depan_base;
        // $img_plat_depanBase = $request->img_platno_url;
        $img_plat_belakangBase = $request->img_plat_belakang_base;
        $tgl_capture = Carbon::parse($request->tgl_capture)->isoFormat('YYYY-MM-DD HH:mm:ss') ?? Carbon::now();
        //Carbon::parse($request->tgl_capture)->isoFormat('YYYY-MM-DD HH:mm:ss');
        $no_kendaraan = $request->no_kendaraan;
        $device = (int)$request->device_id;
        $berat_timbang = (int)$request->berat_timbang ?? 0;

        $tglFolder = Carbon::parse($tgl_capture)->isoFormat('DD-MM-YYYY');
        $tglFile = Carbon::parse($tgl_capture)->isoFormat('DDMMYYYYHHmmss');
        $milliseconds = floor(microtime(true) * 1000);
        $namaFile = $tglFile . '_' . $milliseconds;
        $lokasi = public_path() . '/images/capture/' . $tglFolder;

        $kode_capture = env('KODE_CAPTURE');
        $path_upload_kendaraan = env('PATH_UPLOAD') . '/lhrkendaraan/';
        $path_upload_platnomor = env('PATH_UPLOAD') . '/lhrplatnomor/';
        $image_url_kendaraan = env('IMAGE_URL') . 'lhrkendaraan/';
        $image_url_platnomor = env('IMAGE_URL') . 'lhrplatnomor/';
        // dd($request->all());
        // Jika kode capture 1 maka terdapat validasi, jika selain 1 tanpa validasi
        if ($kode_capture === 1 || $kode_capture === '1') {
            // WIM
            if ($device === 1) {
                if ($berat_timbang === null || $berat_timbang === 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Berat Timbang Tidak Boleh Kosong',
                    ], 401);
                } else {
                    if ($img1 || $img2 || $img3 || $img4 || $img1Url || $img2Url || $img3Url || $img4Url) {
                        if ($img1) {
                            $upload = $img1->move($path_upload_kendaraan, $namaFile . '1.jpg');
                            $img_name = basename($upload);
                            $img_url = $image_url_kendaraan . $namaFile . '1.jpg';
                        } elseif ($img1Url) {
                            $img_url = $img1Url;
                            $img_name = basename($img_url);
                        } else {
                            $img_name = null;
                            $img_url = null;
                        }

                        if ($img2) {
                            $upload2 = $img2->move($path_upload_kendaraan, $namaFile . '2.jpg');
                            $img2_name = basename($upload2);
                            $img2_url = $image_url_kendaraan . $namaFile . '2.jpg';
                        } elseif ($img2Url) {
                            $img2_url = $img2Url;
                            $img2_name = basename($img2_url);
                        } else {
                            $img2_name = null;
                            $img2_url = null;
                        }

                        if ($img3) {
                            $upload3 = $img3->move($path_upload_kendaraan, $namaFile . '3.jpg');
                            $img3_name = basename($upload3);
                            $img3_url = $image_url_kendaraan . $namaFile . '3.jpg';
                        } elseif ($img3Url) {
                            $img3_url = $img3Url;
                            $img3_name = basename($img3_url);
                        } else {
                            $img3_name = null;
                            $img3_url = null;
                        }

                        if ($img4) {
                            $upload4 = $img4->move($path_upload_kendaraan, $namaFile . '4.jpg');
                            $img4_name = basename($upload4);
                            $img4_url = $image_url_kendaraan . $namaFile . '4.jpg';
                        } elseif ($img4Url) {
                            $img4_url = $img4Url;
                            $img4_name = basename($img4_url);
                        } else {
                            $img4_name = null;
                            $img4_url = null;
                        }

                        if ($img_plat_depan) {
                            $upload_plat_depan = $img_plat_depan->move($path_upload_platnomor, $namaFile . 'plat_depan.jpg');
                            $img_plat_depan_name = basename($upload_plat_depan);
                            $img_plat_depan_url = $image_url_platnomor . $namaFile . 'plat_depan.jpg';
                        } elseif ($img_plat_depanUrl) {
                            $img_plat_depan_url = $img_plat_depanUrl;
                            $img_plat_depan_name = basename($img_plat_depan_url);
                        } elseif ($img_plat_depanBase) {
                            $image = str_replace('data:image/png;base64,', '', $img_plat_depanBase);
                            $image = str_replace('data:image/jpg;base64,', '', $img_plat_depanBase);
                            $image = str_replace(' ', '+', $image);
                            // $plat_depan_base = base64_decode($image);
                            $path = $path_upload_platnomor . $namaFile . 'plat_depan.jpg';
                            file_put_contents($path, base64_decode($image));
                            $img_plat_depan_name = basename($path);
                            $img_plat_depan_url = $image_url_platnomor . $namaFile . 'plat_depan.jpg';
                        } else {
                            $img_plat_depan_name = null;
                            $img_plat_depan_url = null;
                        }

                        if ($img_plat_belakang) {
                            $upload_plat_belakang = $img_plat_belakang->move($path_upload_platnomor, $namaFile . 'plat_belakang.jpg');
                            $img_plat_belakang_name = basename($upload_plat_belakang);
                            $img_plat_belakang_url = $image_url_platnomor . $namaFile . 'plat_belakang.jpg';
                        } elseif ($img_plat_belakangUrl) {
                            $img_plat_belakang_url = $img_plat_belakangUrl;
                            $img_plat_belakang_name = basename($img_plat_belakang_url);
                        } elseif ($img_plat_belakangBase) {
                            $image = str_replace('data:image/png;base64,', '', $img_plat_belakangBase);
                            $image = str_replace('data:image/jpg;base64,', '', $img_plat_belakangBase);
                            $image = str_replace(' ', '+', $image);
                            // $plat_depan_base = base64_decode($image);
                            $path2 = $path_upload_platnomor . $namaFile . 'plat_belakang.jpg';
                            file_put_contents($path2, base64_decode($image));
                            $img_plat_belakang_name = basename($path2);
                            $img_plat_belakang_url = $image_url_platnomor . $namaFile . 'plat_belakang.jpg';
                        } else {
                            $img_plat_belakang_name = null;
                            $img_plat_belakang_url = null;
                        }

                        $query = Model::create([
                            'tgl_capture' => $tgl_capture,
                            'no_kendaraan' => $no_kendaraan,
                            'device_id' => $device,
                            'berat_timbang' => $berat_timbang,
                            'panjang_ukur' => (int) $request->panjang_ukur ?? 0,
                            'lebar_ukur' => (int) $request->lebar_ukur ?? 0,
                            'tinggi_ukur' => (int) $request->tinggi_ukur ?? 0,
                            'foh_ukur' => (int) $request->foh_ukur ?? 0,
                            'roh_ukur' => (int) $request->roh_ukur ?? 0,
                            'is_verifikasi' => false,
                            'is_active' => true,
                            'img_name' => $img_name,
                            'img2_name' => $img2_name,
                            'img3_name' => $img3_name,
                            'img4_name' => $img4_name,
                            'img_plat_depan_name' => $img_plat_depan_name,
                            'img_plat_belakang_name' => $img_plat_belakang_name,
                            'img_url' => $img_url,
                            'img2_url' => $img2_url,
                            'img3_url' => $img3_url,
                            'img4_url' => $img4_url,
                            'img_plat_depan_url' => $img_plat_depan_url,
                            'img_plat_belakang_url' => $img_plat_belakang_url,
                            'is_plat' => false,
                            'kd_referensi' => $request->kd_referensi ?? '1',
                            'id_referensi' => $request->id_referensi ?? '',
                        ]);

                        return new Resource(true, __('message.SIMPAN_BERHASIL'), $query);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Image Kendaraan / URL Image Tidak Boleh Kosong',
                        ], 401);
                    }
                }
                // LHR
            } elseif ($device === 2) {
                if ($img1 || $img2 || $img3 || $img4 || $img1Url || $img2Url || $img3Url || $img4Url) {
                    if ($img1) {
                        $upload = $img1->move($path_upload_kendaraan, $namaFile . '1.jpg');
                        $img_name = basename($upload);
                        $img_url = $image_url_kendaraan . $namaFile . '1.jpg';
                    } elseif ($img1Url) {
                        $img_url = $img1Url;
                        $img_name = basename($img_url);
                    } else {
                        $img_name = null;
                        $img_url = null;
                    }

                    if ($img2) {
                        $upload2 = $img2->move($path_upload_kendaraan, $namaFile . '2.jpg');
                        $img2_name = basename($upload2);
                        $img2_url = $image_url_kendaraan . $namaFile . '2.jpg';
                    } elseif ($img2Url) {
                        $img2_url = $img2Url;
                        $img2_name = basename($img2_url);
                    } else {
                        $img2_name = null;
                        $img2_url = null;
                    }

                    if ($img3) {
                        $upload3 = $img3->move($path_upload_kendaraan, $namaFile . '3.jpg');
                        $img3_name = basename($upload3);
                        $img3_url = $image_url_kendaraan . $namaFile . '3.jpg';
                    } elseif ($img3Url) {
                        $img3_url = $img3Url;
                        $img3_name = basename($img3_url);
                    } else {
                        $img3_name = null;
                        $img3_url = null;
                    }

                    if ($img4) {
                        $upload4 = $img4->move($path_upload_kendaraan, $namaFile . '4.jpg');
                        $img4_name = basename($upload4);
                        $img4_url = $image_url_kendaraan . $namaFile . '4.jpg';
                    } elseif ($img4Url) {
                        $img4_url = $img4Url;
                        $img4_name = basename($img4_url);
                    } else {
                        $img4_name = null;
                        $img4_url = null;
                    }

                    if ($img_plat_depan) {
                        $upload_plat_depan = $img_plat_depan->move($path_upload_platnomor, $namaFile . '.jpg');
                        $img_plat_depan_name = basename($upload_plat_depan);
                        $img_plat_depan_url = $image_url_platnomor . $namaFile . '.jpg';
                    } elseif ($img_plat_depanUrl) {
                        $img_plat_depan_url = $img_plat_depanUrl;
                        $img_plat_depan_name = basename($img_plat_depan_url);
                    } elseif ($img_plat_depanBase) {
                        $img_plat_depan_url = $img_plat_depanBase;
                        $img_plat_depan_name = basename($img_plat_depan_url);
                    } else {
                        $img_plat_depan_name = null;
                        $img_plat_depan_url = null;
                    }

                    // if ($img_plat_depan) {
                    //     $upload_plat_depan = $img_plat_depan->move($path_upload_platnomor, $namaFile . '.jpg');
                    //     $img_plat_depan_name = basename($upload_plat_depan);
                    //     $img_plat_depan_url = $image_url_platnomor . $namaFile . '.jpg';
                    // } elseif ($img_plat_depanUrl) {
                    //     $img_plat_depan_url = $img_plat_depanUrl;
                    //     $img_plat_depan_name = basename($img_plat_depan_url);
                    // } elseif ($img_plat_depanBase) {
                    //     $image = str_replace('data:image/png;base64,', '', $img_plat_depanBase);
                    //     $image = str_replace('data:image/jpg;base64,', '', $img_plat_depanBase);
                    //     $image = str_replace(' ', '+', $image);
                    //     // $plat_depan_base = base64_decode($image);
                    //     $path = $path_upload_platnomor . $namaFile . '.jpg';
                    //     file_put_contents($path, base64_decode($image));
                    //     $img_plat_depan_name = basename($path);
                    //     $img_plat_depan_url = $image_url_platnomor . $namaFile . '.jpg';
                    // } else {
                    //     $img_plat_depan_name = null;
                    //     $img_plat_depan_url = null;
                    // }

                    if ($img_plat_belakang) {
                        $upload_plat_belakang = $img_plat_belakang->move($path_upload_platnomor, $namaFile . '.jpg');
                        $img_plat_belakang_name = basename($upload_plat_belakang);
                        $img_plat_belakang_url = $image_url_platnomor . $namaFile . '.jpg';
                    } elseif ($img_plat_belakangUrl) {
                        $img_plat_belakang_url = $img_plat_belakangUrl;
                        $img_plat_belakang_name = basename($img_plat_belakang_url);
                    } elseif ($img_plat_belakangBase) {
                        $image = str_replace('data:image/png;base64,', '', $img_plat_belakangBase);
                        $image = str_replace('data:image/jpg;base64,', '', $img_plat_belakangBase);
                        $image = str_replace(' ', '+', $image);
                        // $plat_depan_base = base64_decode($image);
                        $path2 = $path_upload_platnomor . $namaFile . '.jpg';
                        file_put_contents($path2, base64_decode($image));
                        $img_plat_belakang_name = basename($path2);
                        $img_plat_belakang_url = $image_url_platnomor . $namaFile . '.jpg';
                    } else {
                        $img_plat_belakang_name = null;
                        $img_plat_belakang_url = null;
                    }

                    $query = Model::create([
                        'tgl_capture' => $tgl_capture,
                        'no_kendaraan' => $no_kendaraan,
                        'device_id' => $device,
                        'berat_timbang' => $berat_timbang,
                        'panjang_ukur' => (int) $request->panjang_ukur ?? 0,
                        'lebar_ukur' => (int) $request->lebar_ukur ?? 0,
                        'tinggi_ukur' => (int) $request->tinggi_ukur ?? 0,
                        'foh_ukur' => (int) $request->foh_ukur ?? 0,
                        'roh_ukur' => (int) $request->roh_ukur ?? 0,
                        'is_verifikasi' => false,
                        'is_active' => true,
                        'img_name' => $img_name,
                        'img2_name' => $img2_name,
                        'img3_name' => $img3_name,
                        'img4_name' => $img4_name,
                        'img_plat_depan_name' => $img_plat_depan_name,
                        'img_plat_belakang_name' => $img_plat_belakang_name,
                        'img_url' => $img_url,
                        'img2_url' => $img2_url,
                        'img3_url' => $img3_url,
                        'img4_url' => $img4_url,
                        'img_plat_depan_url' => $img_plat_depan_url,
                        'img_plat_belakang_url' => $img_plat_belakang_url,
                        'is_plat' => false,
                        'kd_referensi' => $request->kd_referensi ?? '1',
                        'id_referensi' => $request->id_referensi ?? '',
                    ]);

                    return new Resource(true, __('message.SIMPAN_BERHASIL'), $query);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Image Kendaraan / URL Image Tidak Boleh Kosong',
                    ], 401);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Device ID Tidak Valid',
                ], 401);
            }
        } else {
            if ($img1) {
                $upload = $img1->move($lokasi, $namaFile . '1.jpg');
                $img_name = basename($upload);
                $img_url = url('/') . '/images/capture/' . $tglFolder . '/' . $namaFile . '1.jpg';
            } else {
                $img_name = null;
                $img_url = null;
            }

            if ($img2) {
                $upload2 = $img2->move($lokasi, $namaFile . '2.jpg');
                $img2_name = basename($upload2);
                $img2_url = url('/') . '/images/capture/' . $tglFolder . '/' . $namaFile . '2.jpg';
            } else {
                $img2_name = null;
                $img2_url = null;
            }

            if ($img3) {
                $upload3 = $img3->move($lokasi, $namaFile . '3.jpg');
                $img3_name = basename($upload3);
                $img3_url = url('/') . '/images/capture/' . $tglFolder . '/' . $namaFile . '3.jpg';
            } else {
                $img3_name = null;
                $img3_url = null;
            }

            if ($img4) {
                $upload4 = $img4->move($lokasi, $namaFile . '4.jpg');
                $img4_name = basename($upload4);
                $img4_url = url('/') . '/images/capture/' . $tglFolder . '/' . $namaFile . '4.jpg';
            } else {
                $img4_name = null;
                $img4_url = null;
            }

            if ($img_plat_depan) {
                $upload_plat_depan = $img_plat_depan->move($lokasi, $namaFile . 'plat_depan.jpg');
                $img_plat_depan_name = basename($upload_plat_depan);
                $img_plat_depan_url = url('/') . '/images/capture/' . $tglFolder . '/' . $namaFile . 'plat_depan.jpg';
            } else {
                $img_plat_depan_name = null;
                $img_plat_depan_url = null;
            }

            if ($img_plat_belakang) {
                $upload_plat_belakang = $img_plat_belakang->move($lokasi, $namaFile . 'plat_belakang.jpg');
                $img_plat_belakang_name = basename($upload_plat_belakang);
                $img_plat_belakang_url = url('/') . '/images/capture/' . $tglFolder . '/' . $namaFile . 'plat_belakang.jpg';
            } else {
                $img_plat_belakang_name = null;
                $img_plat_belakang_url = null;
            }

            $query = Model::create([
                'tgl_capture' => $tgl_capture,
                'no_kendaraan' => $no_kendaraan,
                'device_id' => $device,
                'berat_timbang' => $berat_timbang,
                'panjang_ukur' => (int) $request->panjang_ukur ?? 0,
                'lebar_ukur' => (int) $request->lebar_ukur ?? 0,
                'tinggi_ukur' => (int) $request->tinggi_ukur ?? 0,
                'foh_ukur' => (int) $request->foh_ukur ?? 0,
                'roh_ukur' => (int) $request->roh_ukur ?? 0,
                'is_verifikasi' => false,
                'is_active' => true,
                'img_name' => $img_name,
                'img2_name' => $img2_name,
                'img3_name' => $img3_name,
                'img4_name' => $img4_name,
                'img_plat_depan_name' => $img_plat_depan_name,
                'img_plat_belakang_name' => $img_plat_belakang_name,
                'img_url' => $img_url,
                'img2_url' => $img2_url,
                'img3_url' => $img3_url,
                'img4_url' => $img4_url,
                'img_plat_depan_url' => $img_plat_depan_url,
                'img_plat_belakang_url' => $img_plat_belakang_url,
                'is_plat' => false,
                'id_lhr' => $request->id_lhr ?? '',
                'id_penimbangan' => $request->id_penimbangan ?? '',
            ]);

            return new Resource(true, __('message.SIMPAN_BERHASIL'), $query);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Model  $query
     * @return \Illuminate\Http\Response
     */
    public function show(Model $capture)
    {
        return new Resource(true, __('message.GET_BERHASIL'), $capture);
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
    public function update(Request $request, Model $capture)
    {
        $validator = Validator::make($request->all(), [
            'no_kendaraan' => 'required|max:30',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $capture->update($request->all());
        return new Resource(true, __('message.SIMPAN_BERHASIL'), $capture);
    }

    public function updateVerif(Request $request)
    {
        $ids = $request->id;
        $nokend = $request->no_kendaraan;
        $query = Model::whereIn('id', explode(",", $ids))->update([
            'is_verifikasi'     => $request->input('is_verifikasi') ?? true,
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
    public function destroy(Model $capture)
    {
        $query = $capture->delete();

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
