<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;

class Lokasi extends Model
{
    use HasFactory, SoftDeletes, CrudBy;

    protected $table = 't_lokasi';
    protected $fillable = ['kode', 'nama', 'kota_kab_id', 'lat', 'lon', 'is_active', 'ttd_url'];
    protected $dates = ['deleted_at'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'nama_lengkap', 'email']);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->select(['id', 'nama_lengkap', 'email']);;
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by')->select(['id', 'nama_lengkap', 'email']);;
    }

    public function kotakab()
    {
        return $this->belongsTo(Kotakab::class, 'kota_kab_id')->select(['id', 'kode', 'nama', 'provinsi_id'])->where('is_active', true)->with(['provinsi']);
    }
}
