<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;
use App\Traits\UUID;

class Wim extends Model
{
    use HasFactory, SoftDeletes, CrudBy, UUID;

    protected $table = 'jt_log_wim';
    protected $fillable = [
        'no_kendaraan',
        'tgl_penimbangan',
        'is_transaksi',
        'wim_kode',
        'kode_uppkb',
        'foto_depan_name',
        'foto_depan_url',
        'foto_plat_no_name',
        'foto_plat_no_url',
        'sumbu',
        'wim_berat',
        'wim_panjang',
        'wim_lebar',
        'wim_tinggi',
        'wim_foh',
        'wim_roh',
        'wim_kecepatan',
        'device_id',
        'is_status'
    ];
    protected $dates = ['deleted_at'];
}
