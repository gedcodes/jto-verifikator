<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;
use App\Traits\UUID;

class Capture extends Model
{
    use HasFactory, SoftDeletes, CrudBy, UUID;

    protected $table = 'jt_vr_data';
    protected $fillable = [
        'no_kendaraan',
        'tgl_capture',
        'img_name',
        'img_url',
        'img2_name',
        'img2_url',
        'img3_name',
        'img3_url',
        'img4_name',
        'img4_url',
        'img_plat_depan_name',
        'img_plat_depan_url',
        'img_plat_belakang_name',
        'img_plat_belakang_url',
        'berat_timbang',
        'panjang_ukur',
        'lebar_ukur',
        'tinggi_ukur',
        'foh_ukur',
        'roh_ukur',
        'is_verifikasi',
        'device_id',
        'is_active',
        'is_plat',
        'kd_referensi',
        'id_referensi',
        'id_gol_ai',
        'is_blue',
        'masa_berlaku_blue',
        'vcode',
        'rfid',
        'kode_uppkb',
        'sync_to_pusat',
        'sync_from_pusat',
        'id_sensor',
        'kode_sensor',
        'is_melanggar',
    ];
    protected $dates = ['deleted_at'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'nama_lengkap', 'email']);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->select(['id', 'nama_lengkap', 'email']);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by')->select(['id', 'nama_lengkap', 'email']);
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id')->select(['id', 'kode', 'nama']);
    }
    public function golkend()
    {
        return $this->belongsTo(GolKendaraan::class, 'id_gol_ai')->select(['id', 'id_jns_kendaraan', 'desc_gol_ai']);
    }
}
