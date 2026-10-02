<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;

class Gwrouting extends Model
{
    use HasFactory, SoftDeletes, CrudBy;

    protected $table = 't_gateway_routing';
    protected $fillable = ['kementerian_id', 'bptd_id', 'provinsi_id', 'kota_kab_id', 'jenis_perangkat_id', 'vendor_id', 'lokasi_id', 'gateway_route', 'deskripsi', 'is_active'];
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

    public function kementerian()
    {
        return $this->belongsTo(Kementerian::class, 'kementerian_id')->select(['id', 'kode', 'nama', 'deskripsi'])->where('is_active', true);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id')->select(['id', 'kode', 'nama', 'deskripsi'])->where('is_active', true);
    }

    public function jenisperangkat()
    {
        return $this->belongsTo(JenisPerangkat::class, 'jenis_perangkat_id')->select(['id', 'kode', 'nama', 'deskripsi'])->where('is_active', true);
    }

    public function bptd()
    {
        return $this->belongsTo(Bptd::class, 'bptd_id')->select(['id', 'kode', 'nama'])->where('is_active', true);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id')->select(['id', 'kode', 'nama'])->where('is_active', true);
    }

    public function kotakab()
    {
        return $this->belongsTo(Kotakab::class, 'kota_kab_id')->select(['id', 'kode', 'nama'])->where('is_active', true);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id')->select(['id', 'kode', 'nama', 'lat', 'lon'])->where('is_active', true);
    }
}
