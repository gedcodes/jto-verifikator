<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceResource as Resource;
use App\Models\Device as Model;
// use App\Models\Kementerian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;

class DeviceController extends Controller
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
        $total = Model::count();
        $query = Model::with(['createdBy', 'updatedBy', 'deletedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

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
        // $tanggal = $request->tanggal ?? date('Y-m-d');
        // $tanggalawal = $tanggal . ' 00:00:01';
        // $tanggalakhir = $tanggal . ' 23:59:59';
        $total = Model::count();

        $query = Model::with(['createdBy', 'updatedBy', 'deletedBy'])->where('is_active', true)->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

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

        $query = Model::onlyTrashed()->with(['createdBy', 'updatedBy', 'deletedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

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
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $query = Model::create($request->all());


        return new Resource(true, __('message.SIMPAN_BERHASIL'), $query);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Model  $query
     * @return \Illuminate\Http\Response
     */
    public function show(Model $device)
    {
        return new Resource(true, __('message.GET_BERHASIL'), $device);
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
    public function update(Request $request, Model $device)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $device->update($request->all());
        return new Resource(true, __('message.SIMPAN_BERHASIL'), $device);
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
    public function destroy(Model $device)
    {
        $query = $device->delete();

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
