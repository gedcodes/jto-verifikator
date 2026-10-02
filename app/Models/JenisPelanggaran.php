<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;

class JenisPelanggaran extends Model
{
    use HasFactory, SoftDeletes, CrudBy;

    protected $table = 'jt_jenis_pelanggaran';
    protected $fillable = ['kode', 'nama', 'is_active'];
    // protected $guarded = ['id'];
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

    // public function pasal()
    // {
    //     return $this->belongsTo(Pasal::class)->select(['id', 'no_pasal', 'pasal', 'desk_pasal', 'denda_maks', 'keterangan'])->where('is_active', true);
    // }

    // public function detailpelanggaran()
    // {
    //     return $this->hasMany(DetailPelanggaran::class);
    // }
}
