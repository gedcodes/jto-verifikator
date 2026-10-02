<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 'id';
        $sortby = $request->sortby ?? 'asc';
        $kode = env('MIX_KODE_UPPKB');
        $query = DB::table('jt_shift')->where('kode_uppkb', $kode)->orderBy($sort, $sortby)->get();

        //return collection of posts as a resource
        return response()->json([
            'success' => true,
            'message' => 'Berhasil',
            'data' => $query
        ], 200);
        // return Resource::collection($query);
    }

    public function active(Request $request)
    {
        $sort = $request->sort ?? 'id';
        $sortby = $request->sortby ?? 'asc';
        $kode = env('MIX_KODE_UPPKB');
        $query = DB::table('jt_shift')->where('kode_uppkb', $kode)->where('is_active', true)->orderBy($sort, $sortby)->get();

        //return collection of posts as a resource
        return response()->json([
            'success' => true,
            'message' => 'Berhasil',
            'data' => $query
        ], 200);
    }
}
