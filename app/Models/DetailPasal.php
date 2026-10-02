<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;
use App\Traits\UUID;

class DetailPasal extends Model
{
    use HasFactory, SoftDeletes, CrudBy, UUID;

    protected $table = 'vr_detail_pasal';
    protected $fillable = ['pelanggaran_id', 'pasal_id', 'no_pasal', 'desk_pasal', 'is_active'];
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

    public function pasal()
    {
        return $this->belongsTo(Pasal::class, 'pasal_id')->select(['id', 'no_pasal', 'pasal', 'desk_pasal', 'denda_maks', 'keterangan', 'is_active']);
    }
}
