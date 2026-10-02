<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;

class Regu extends Model
{
    use HasFactory, SoftDeletes, CrudBy;

    protected $table = 'jt_regu';
    protected $fillable = ['kode', 'nama', 'shift_id', 'is_active'];
    //protected $dates = ['deleted_at'];

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
