<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;
use App\Traits\UUID;

class DetailCapture extends Model
{
    use HasFactory, SoftDeletes, CrudBy, UUID;

    protected $table = 'vr_detail_capture';
    protected $fillable = ['pelanggaran_id', 'jt_vr_data_id', 'img_name', 'img_url', 'img2_name', 'img2_url', 'img3_name', 'img3_url', 'img4_name', 'img4_url', 'img_plat_depan_name', 'img_plat_depan_url', 'img_plat_belakang_name', 'img_plat_belakang_url', 'tgl_capture', 'is_active', 'is_plat', 'device_id'];
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

    // public function pelanggaran()
    // {
    //     return $this->belongsTo(Verifikasi::class, 'pelanggaran_id')->select(['id', 'pelanggaran_id', 'tgl_verifikasi', 'capture_id', 'ppns', 'is_archive', 'is_verifikasi', 'no_kendaraan', 'is_active']);
    // }

    // public function capture()
    // {
    //     return $this->belongsTo(Capture::class, 'jt_vr_data_id');
    // }
}
