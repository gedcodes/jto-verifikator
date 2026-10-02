<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;
use App\Traits\UUID;

class Archive extends Model
{
    use HasFactory, SoftDeletes, CrudBy, UUID;

    protected $table = 'vr_archive';
    protected $fillable = [
        'tgl_archive',
        'no_ref',
        'kode_uppkb',
        'regu_id',
        'shift_id',
        'no_kendaraan',
        'no_uji',
        'tgl_uji',
        'tgl_masa_berlaku',
        'nama_pemilik',
        'alamat_pemilik',
        'jbi_uji',
        'mst_uji',
        'jenis_kendaraan_id',
        'jenis_kendaraan',
        'sumbu_id',
        'sumbu',
        'kategori_kepemilikan_id',
        'device_id',
        'petugas_id',
        'keterangan',
        'berat_timbang',
        'kelebihan_berat',
        'prosen_lebih',
        'panjang_ukur',
        'panjang_utama',
        'panjang_toleransi',
        'panjang_lebih',
        'lebar_ukur',
        'lebar_utama',
        'lebar_toleransi',
        'lebar_lebih',
        'tinggi_ukur',
        'tinggi_utama',
        'tinggi_toleransi',
        'tinggi_lebih',
        'foh_ukur',
        'foh_utama',
        'foh_toleransi',
        'foh_lebih',
        'roh_ukur',
        'roh_utama',
        'roh_toleransi',
        'roh_lebih',
        'qrcode_name',
        'qrcode_url',
        'tgl_capture',
        'keterangan_id'
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

    public function regu()
    {
        return $this->belongsTo(Regu::class, 'regu_id')->select(['id', 'kode', 'nama']);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id')->select(['id', 'kode', 'nama']);
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id')->select(['id', 'kode_uppkb', 'nip', 'nama']);
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id')->select(['id', 'kode', 'nama']);
    }

    public function detailcapture()
    {
        return $this->hasMany(DetailCapture::class);
    }
}
