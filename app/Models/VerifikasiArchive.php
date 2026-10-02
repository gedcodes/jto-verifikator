<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;
use App\Traits\UUID;

class VerifikasiArchive extends Model
{
    use HasFactory, SoftDeletes, CrudBy, UUID;

    protected $table = 'jt_vr_data_archive';
    protected $fillable = ['no_kendaraan', 'tgl_capture', 'img_name', 'img_url', 'img2_name', 'img2_url', 'img3_name', 'img3_url', 'img4_name', 'img4_url', 'img_plat_depan_name', 'img_plat_depan_url', 'img_plat_belakang_name', 'img_plat_belakang_url', 'berat_timbang', 'panjang_ukur', 'lebar_ukur', 'tinggi_ukur', 'foh_ukur', 'roh_ukur', 'is_verifikasi', 'device_id', 'is_active', 'is_plat', 'kd_referensi', 'id_referensi', 'keterangan', 'keterangan_id', 'jt_vr_data_id'];
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
}
