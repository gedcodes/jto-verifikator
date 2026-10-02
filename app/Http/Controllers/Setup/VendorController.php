<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource as Resource;
use App\Models\Vendor as Model;
// use App\Models\Kementerian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;

class VendorController extends Controller
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
        $query = Model::with(['createdBy', 'updatedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

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

        $query = Model::with(['createdBy', 'updatedBy'])->where('is_active', true)->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => (int) $total
        ];

        return new Resource(true, __('message.GET_BERHASIL'), $query, $meta);
    }

    public function trash(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $sortby = $request->sortby ?? 'desc';
        $page = $request->page - 1 ?? 0;
        $limit = $request->limit ?? 99999;
        $total = Model::count();

        $query = Model::onlyTrashed()->with(['createdBy', 'updatedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

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
            'kode' => 'required|max:255|unique:t_vendor,kode',
            'nama' => 'required|min:4|max:255|unique:t_vendor,nama',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {

            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $input = $request->all();

        $uploadFolder = 'images/vendor';
        if ($image = $request->file('image')) {
            $imageName = date('YmdHis') . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = $image->storeAs($uploadFolder, $imageName, 'public');
            $input['logo_name'] = basename($destinationPath);
            $input['logo_url'] = url('/') . Storage::url($destinationPath);
        }

        $query = Model::create($input);


        return new Resource(true, __('message.SIMPAN_BERHASIL'), $query);
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
    public function update(Request $request, Model $vendor)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|max:255|unique:t_vendor,kode,' . $vendor->id,
            'nama' => 'required|min:4|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $input = $request->all();

        $uploadFolder = 'images/vendor';
        $doc = $vendor->where('id', $vendor->id)->first();
        // if ($request->hasFile('image')) {
        // print_r($request->file('image'));
        // $file_path = public_path() . '/' . 'storage/' . $uploadFolder . '/' . $doc['logo_name'];

        // if (file_exists($file_path)) {
        //     unlink($file_path);
        // }


        if ($request->file('image')) {
            $image = $request->file('image');
            $imageName = date('YmdHis') . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = $image->storeAs($uploadFolder, $imageName, 'public');
            $input['logo_name'] = basename($destinationPath);
            $input['logo_url'] = url('/') . Storage::url($destinationPath);
        } else {
            $input['logo_name'] = $doc['logo_name'];
            $input['logo_url'] = $doc['logo_url'];
        }

        $vendor->update($input);
        return new Resource(true, __('message.SIMPAN_BERHASIL'), $vendor);
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
    public function destroy(Model $vendor)
    {
        $query = $vendor->delete();

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

    public function trashArr(Request $request)
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

        $uploadFolder = 'images/vendor';
        $doc = Model::withTrashed()->whereIn('id', explode(",", $ids))->get();
        foreach ($doc as $p) {
            $file_path = public_path() . '/' . 'storage/' . $uploadFolder . '/' . $p->logo_name;

            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
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
