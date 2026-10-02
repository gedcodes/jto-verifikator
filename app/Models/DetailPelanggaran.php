<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;
use App\Traits\UUID;

class DetailPelanggaran extends Model
{
    use HasFactory, SoftDeletes, CrudBy, UUID;

    protected $table = 'vr_detail_pelanggaran';
    protected $fillable = ['pelanggaran_id', 'jenis_pelanggaran_id', 'kode_pelanggaran', 'deskripsi', 'is_active'];
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

    public function jenispelanggaran()
    {
        return $this->belongsTo(JenisPelanggaran::class, 'jenis_pelanggaran_id')->select(['id', 'kode', 'nama', 'is_active']);
    }
}
