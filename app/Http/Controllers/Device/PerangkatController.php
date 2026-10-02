<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Http\Resources\PerangkatResource as Resource;
use App\Models\Perangkat as Model;
use App\Models\Perangkat;
use App\Models\Gwrouting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PerangkatController extends Controller
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
        $query = Model::with(['lokasi', 'gwrouting', 'createdBy', 'updatedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

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

        $query = Model::with(['lokasi', 'gwrouting', 'createdBy', 'updatedBy'])->where('is_active', true)->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

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

        $query = Model::onlyTrashed()->with(['lokasi', 'gwrouting', 'createdBy', 'updatedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

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
        $input = $request->all();
        $qroute = Gwrouting::where('id', $input['gateway_routing_id'])->first();
        $kode_perangkat = $qroute->gateway_route . $input['kode'];

        $input['kode_perangkat'] = $kode_perangkat;
        // print_r($input);
        //            'kode' => 'required|max:4|unique:t_device,kode,' . $input['kode_perangkat'],

        $validator = Validator::make($request->all(), [
            'lokasi_id' => 'required',
            'gateway_routing_id' => 'required',
            'kode' => [
                "required",
                Rule::unique('t_device')->where(fn ($query) => $query->where('kode_perangkat', $kode_perangkat)) //assuming the request has platform information
            ],
            'nama' => 'required|min:4|max:255|unique:t_device,nama',
        ]);

        if ($validator->fails()) {

            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $query = Model::create($input);

        return new Resource(true, __('message.SIMPAN_BERHASIL'), $query);
    }

    public function storeArr(Request $request)
    {
        // print_r($request->all());
        $validator = Validator::make($request->all(), [
            'lokasi_id' => 'required',
            'kode' => 'required|max:4|unique:t_device,kode,' . $perangkat->gateway_routing_id,
            'nama' => 'required|min:4|max:255|unique:t_device,nama',
        ]);

        if ($validator->fails()) {

            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $input = $request->all();
        $gatewayroute = $request->gatewayroute;

        if (count($gatewayroute) > 0) {
            // $model = new Perangkat;
            // $arrInput = array();
            for ($i = 0; $i < count($gatewayroute); $i++) {
                Model::create([
                    'lokasi_id' => $input['lokasi_id'],
                    'gateway_routing_id' => $gatewayroute[$i],
                    'kode' => $input['kode'],
                    'nama' => $input['nama'],
                    'deskripsi' => $input['deskripsi'],
                    'ip_address' => $input['ip_address'],
                    'ip_gateway' => $input['ip_gateway'],
                    'ip_subnet' => $input['ip_subnet'],
                ]);
                // echo count($gatewayroute) . ' == ' . ($i + 1);
                if (count($gatewayroute) == ($i + 1)) {
                    return response()->json([
                        "success" => true,
                        "message" => __('message.SIMPAN_BERHASIL'),
                    ]);
                }
            }
        } else {
            return $this->sendError(__('message.SIMPAN_GAGAL'), ['gatewayroute' => 'Gatewa Route < 0'], 422);
        }

        // $query = Model::create($request->all());

        // return new Resource(true, __('message.SIMPAN_BERHASIL'), $query);
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
    public function update(Request $request, Model $perangkat)
    {
        $input = $request->all();
        $qroute = Gwrouting::where('id', $input['gateway_routing_id'])->first();

        $kode_perangkat = $qroute->gateway_route . $input['kode'];
        $input['kode_perangkat'] = $kode_perangkat;
        $validator = Validator::make($request->all(), [
            'lokasi_id' => 'required',
            'gateway_routing_id' => 'required',
            'kode' => [
                "required",
                Rule::unique('t_device')->where(fn ($query) => $query->where('id', '<>', $perangkat->id)->where('kode_perangkat', '=', $kode_perangkat)) //assuming the request has platform information
            ],
            'nama' => 'required|min:4|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }



        $perangkat->update($input);

        return new Resource(true, __('message.SIMPAN_BERHASIL'), $perangkat);
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
    public function destroy(Model $perangkat)
    {
        $query = $perangkat->delete();

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
