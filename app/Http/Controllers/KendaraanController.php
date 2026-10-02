<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\KendaraanResource as Resource;
use App\Models\Kendaraan as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $sort = $request->sort ?? 'created_at';
        // $sortby = $request->sortby ?? 'desc';
        // $page = $request->page - 1 ?? 0;
        // $limit = $request->limit ?? 25;
        $tgl_antrian = $request->tanggal;
        $nokend = $request->nokend;
        $sink = DB::table('jt_penimbangan')
            ->where('tgl_antrian', $tgl_antrian)
            ->where('no_kendaraan', $nokend)
            ->first();

        if ($sink) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Mengambil Data',
                'data' => (object)$sink
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Mengambil Data',
            ], 200);
        }
    }

    public function getbynokend(Request $request)
    {
        $no_kendaraan = $request->no_kendaraan;
        $total = Model::count();

        $query = Model::where('no_reg_kend', $no_kendaraan)->get();

        return new Resource(true, __('message.GET_BERHASIL'), $query);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
