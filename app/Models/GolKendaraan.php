<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GolKendaraan extends Model
{
    use HasFactory;

    protected $table = 'jt_gol_ai';
    protected $fillable = ['id_kategori_gol_ai', 'id_jns_kendaraan', 'desc_gol_ai'];
    // protected $dates = ['deleted_at'];

    // public function createdBy()
    // {
    //     return $this->belongsTo(User::class, 'created_by')->select(['id', 'nama_lengkap', 'email']);
    // }

    // public function updatedBy()
    // {
    //     return $this->belongsTo(User::class, 'updated_by')->select(['id', 'nama_lengkap', 'email']);;
    // }

    // public function deletedBy()
    // {
    //     return $this->belongsTo(User::class, 'deleted_by')->select(['id', 'nama_lengkap', 'email']);;
    // }
}
