<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;

class Perangkat extends Model
{
    use HasFactory, SoftDeletes, CrudBy;

    protected $table = 't_device';
    protected $fillable = ['lokasi_id', 'gateway_routing_id', 'kode', 'kode_perangkat', 'nama', 'deskripsi', 'ip_address', 'ip_subnet', 'ip_gateway', 'ip_dns', 'is_active'];
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

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id')->select(['id', 'kota_kab_id', 'kode', 'nama', 'lat', 'lon'])->where('is_active', true)->with(['kotakab']);
    }

    public function gwrouting()
    {
        return $this->belongsTo(Gwrouting::class, 'gateway_routing_id')->select(['id', 'kementerian_id', 'bptd_id', 'provinsi_id', 'kota_kab_id', 'jenis_perangkat_id', 'vendor_id', 'lokasi_id', 'gateway_route', 'deskripsi'])->where('is_active', true)->with(['kementerian', 'vendor', 'jenisperangkat', 'kotakab', 'provinsi', 'bptd', 'lokasi']);
    }
}
