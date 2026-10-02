<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Http\Resources\GwroutingResource as Resource;
use App\Models\Bptd;
use App\Models\Gwrouting as Model;
use App\Models\JenisPerangkat;
use App\Models\Kementerian;
use App\Models\Kotakab;
use App\Models\Provinsi;
use App\Models\Vendor;
use Exception;
// use App\Models\Kementerian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;

class GwroutingController extends Controller
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
        $limit = $request->limit ?? 99999;
        $total = Model::count();
        $jenis_perangkat_id = $request->jpid;
        $vendor_id = $request->vid;

        if ($jenis_perangkat_id && $vendor_id) {
            $query = Model::with(['kementerian', 'vendor', 'jenisperangkat', 'bptd', 'provinsi', 'kotakab', 'lokasi', 'createdBy', 'updatedBy'])->where(['jenis_perangkat_id' => $jenis_perangkat_id, 'vendor_id' => $vendor_id])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
        } else if ($vendor_id) {
            $query = Model::with(['kementerian', 'vendor', 'jenisperangkat', 'bptd', 'provinsi', 'kotakab', 'lokasi', 'createdBy', 'updatedBy'])->where(['vendor_id' => $vendor_id])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
        } else if ($jenis_perangkat_id) {
            $query = Model::with(['kementerian', 'vendor', 'jenisperangkat', 'bptd', 'provinsi', 'kotakab', 'lokasi', 'createdBy', 'updatedBy'])->where(['jenis_perangkat_id' => $jenis_perangkat_id])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
        } else {
            $query = Model::with(['kementerian', 'vendor', 'jenisperangkat', 'bptd', 'provinsi', 'kotakab', 'lokasi', 'createdBy', 'updatedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
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
        $limit = $request->limit ?? 99999;
        $total = Model::count();
        $jenis_perangkat_id = $request->jpid;
        $vendor_id = $request->vid;

        if ($jenis_perangkat_id && $vendor_id) {
            $query = Model::with(['kementerian', 'vendor', 'jenisperangkat', 'bptd', 'provinsi', 'kotakab', 'lokasi', 'createdBy', 'updatedBy'])->where(['jenis_perangkat_id' => $jenis_perangkat_id, 'vendor_id' => $vendor_id, 'is_active' => true])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
        } else if ($vendor_id) {
            $query = Model::with(['kementerian', 'vendor', 'jenisperangkat', 'bptd', 'provinsi', 'kotakab', 'lokasi', 'createdBy', 'updatedBy'])->where(['vendor_id' => $vendor_id, 'is_active' => true])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
        } else if ($jenis_perangkat_id) {
            $query = Model::with(['kementerian', 'vendor', 'jenisperangkat', 'bptd', 'provinsi', 'kotakab', 'lokasi', 'createdBy', 'updatedBy'])->where(['jenis_perangkat_id' => $jenis_perangkat_id, 'is_active' => true])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
        } else {
            $query = Model::with(['kementerian', 'vendor', 'jenisperangkat', 'bptd', 'provinsi', 'kotakab', 'lokasi', 'createdBy', 'updatedBy'])->where('is_active', true)->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();
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
        $limit = $request->limit ?? 99999;
        $total = Model::count();

        $query = Model::onlyTrashed()->with(['kementerian', 'vendor', 'jenisperangkat', 'bptd', 'provinsi', 'kotakab', 'lokasi', 'createdBy', 'updatedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => (int) $total
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $query, $meta);
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
        // try {
        //     DB::beginTransaction();

        //     DB::commit();
        // }catch(Exception $e) {
        //     DB::rollback();
        // }
        $validator = Validator::make($request->all(), [
            'kementerian_id' => 'required',
            'bptd_id' => 'required',
            'provinsi_id' => 'required',
            'kota_kab_id' => 'required',
            'jenis_perangkat_id' => 'required',
            'vendor_id' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $input = $request->all();

        $nasional = Kementerian::where('id', $input['kementerian_id'])->first()->kode;
        $bptd = Bptd::where('id', $input['bptd_id'])->first()->kode;
        $provinsi = Provinsi::where('id', $input['provinsi_id'])->first()->kode;
        $kotakab = Kotakab::where('id', $input['kota_kab_id'])->first()->ukode;
        $jenisperangkat = JenisPerangkat::where('id', $input['jenis_perangkat_id'])->first()->kode;
        $vendor = Vendor::where('id', $input['vendor_id'])->first();

        // $input['gateway_route'] = $vendor->kode. sprintf("%02d", (int) $jenisperangkat). sprintf("%06d", (int) $jenisperangkat);
        // print_r((int) $nasional . '.' . (int) $bptd . '.' . (int) $provinsi . '.' . (int) $kotakab . '.' . (int) $jenisperangkat . '.' . $vendor->nama);
        $query = Model::create($request->all());

        if ($query) {
            $last_id = $query->id;
            $tahun = date('y');
            $gateway_route = $vendor->kode . sprintf("%02d", (int) $jenisperangkat) . sprintf("%03d", (int) $tahun) . sprintf("%03d", (int) $last_id);
            $deskripsi = (int) $nasional . '.' . (int) $bptd . '.' . (int) $provinsi . '.' . (int) $kotakab . '.' . (int) $jenisperangkat . '.' . $vendor->nama . '.' . $gateway_route;
            $update = Model::where('id', $last_id)->update([
                'gateway_route' => $gateway_route,
                'deskripsi' => $deskripsi
            ]);

            $last_data = Model::where('id', $last_id)->first();
            if ($update) {
                return new Resource(true, __('message.SIMPAN_BERHASIL'), $last_data);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('message.SIMPAN_GAGAL'),
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => __('message.SIMPAN_GAGAL'),
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Model  $query
     * @return \Illuminate\Http\Response
     */
    public function show(Model $query)
    {
        return new Resource(true, __('message.GET_BERHASIL'), $query);
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
    public function update(Request $request, Model $gwrouting)
    {
        $validator = Validator::make($request->all(), [
            'kementerian_id' => 'required',
            'bptd_id' => 'required',
            'provinsi_id' => 'required',
            'kota_kab_id' => 'required',
            'jenis_perangkat_id' => 'required',
            'vendor_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $input = $request->all();
        $nasional = Kementerian::where('id', $input['kementerian_id'])->first()->kode;
        $bptd = Bptd::where('id', $input['bptd_id'])->first()->kode;
        $provinsi = Provinsi::where('id', $input['provinsi_id'])->first()->kode;
        $kotakab = Kotakab::where('id', $input['kota_kab_id'])->first()->ukode;
        $jenisperangkat = JenisPerangkat::where('id', $input['jenis_perangkat_id'])->first()->kode;
        $vendor = Vendor::where('id', $input['vendor_id'])->first();
        $tahun = date('y');
        $gateway_route = $vendor->kode . sprintf("%02d", (int) $jenisperangkat) . sprintf("%03d", (int) $tahun) . sprintf("%03d", (int) $gwrouting->id);
        $deskripsi = (int) $nasional . '.' . (int) $bptd . '.' . (int) $provinsi . '.' . (int) $kotakab . '.' . (int) $jenisperangkat . '.' . $vendor->nama . '.' . $gateway_route;

        $input['gateway_route'] = $gateway_route;
        $input['deskripsi'] = $deskripsi;

        $gwrouting->update($input);
        return new Resource(true, __('message.SIMPAN_BERHASIL'), $gwrouting);
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
    public function destroy(Model $gwrouting)
    {
        $query = $gwrouting->delete();

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
