<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\BptdPostRequest;
use App\Http\Resources\BptdResource;
use App\Models\Bptd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class BptdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $bptd = Bptd::latest()->paginate($perPage = 15, $columns = ['*'], $pageName = 'meta')->sortByDesc("created_at");
        // return response()->json([
        //     "success" => true,
        //     "message" => __('message.GET_BERHASIL'),
        //     "data" => $bptd
        // ]);

        $sort = $request->sort ?? 'created_at';
        $sortby = $request->sortby ?? 'desc';
        $page = $request->page - 1 ?? 0;
        $limit = $request->limit ?? 99999;
        $total = Bptd::count();
        $bptd = Bptd::with(['createdBy', 'updatedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => (int) $total
        ];
        //return collection of posts as a resource
        return new BptdResource(true, __('message.GET_BERHASIL'), $bptd, $meta);
        // return BptdResource::collection($bptd);
    }

    public function active(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $sortby = $request->sortby ?? 'desc';
        $page = $request->page - 1 ?? 0;
        $limit = $request->limit ?? 99999;
        $total = Bptd::count();

        $bptd = Bptd::with(['createdBy', 'updatedBy'])->where('is_active', true)->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => (int) $total
        ];

        return new BptdResource(true, __('message.GET_BERHASIL'), $bptd, $meta);
    }

    public function trash(Request $request)
    {
        $sort = $request->sort ?? 'created_at';
        $sortby = $request->sortby ?? 'desc';
        $page = $request->page - 1 ?? 0;
        $limit = $request->limit ?? 99999;
        $total = Bptd::count();

        $bptd = Bptd::onlyTrashed()->with(['createdBy', 'updatedBy'])->orderBy($sort, $sortby)->skip($page)->limit($limit)->get();

        $meta = [
            'sort' => $sort,
            'sortby' => $sortby,
            'page' => $page <= 0 ? 1 : (int) $page,
            'limit' => (int) $limit,
            'totalData' => (int) $total
        ];

        return new BptdResource(true, __('message.GET_BERHASIL'), $bptd, $meta);
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
            'kode' => 'required|max:255|unique:t_bptd,kode',
            'nama' => 'required|min:4|max:255|unique:t_bptd,nama',
        ]);

        if ($validator->fails()) {

            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $query = Bptd::create($request->all());


        return new BptdResource(true, __('message.SIMPAN_BERHASIL'), $query);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bptd  $bptd
     * @return \Illuminate\Http\Response
     */
    public function show(Bptd $bptd)
    {
        // return [
        //     "status" => 1,
        //     "data" => $bptd
        // ];
        // $success['']
        return new BptdResource(true, __('message.GET_BERHASIL'), $bptd);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bptd  $bptd
     * @return \Illuminate\Http\Response
     */
    public function edit(Bptd $bptd)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bptd  $bptd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bptd $bptd)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|max:255|unique:t_bptd,kode,' . $bptd->id,
            'nama' => 'required|min:4|max:255',
        ]);
        //check if validation fails
        if ($validator->fails()) {
            // return response()->json($validator->errors(), 422);
            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $bptd->update($request->all());
        return new BptdResource(true, __('message.SIMPAN_BERHASIL'), $bptd);
    }

    public function updateActive(Request $request)
    {
        $ids = $request->id;
        $query = Bptd::whereIn('id', explode(",", $ids))->update([
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
     * @param  \App\Models\Bptd  $bptd
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bptd $bptd)
    {
        // $bptd::find($id)->delete();
        $query = $bptd->delete();
        // $bptd->delete();
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
        // $bptd::find($id)->delete();
        $ids = $request->id;
        $query = Bptd::whereIn('id', explode(",", $ids))->delete();
        // $bptd->delete();
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

        $query = Bptd::withTrashed()->whereIn('id', explode(",", $ids))->forceDelete();

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
        $query = Bptd::withTrashed()->find($id)->restore();

        if ($query) {
            // $data = Bptd::whereId($id);
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
        // return new BptdResource(true, __('message.RESTORE_BERHASIL'), $data);
    }

    public function restoreAll()
    {
        $query = Bptd::onlyTrashed()->restore();
        if ($query) {
            // $data = Bptd::whereId($id);
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
        // return new BptdResource(true, __('message.RESTORE_BERHASIL'), $query);
    }
}
