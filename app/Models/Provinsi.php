<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;
use App\Models\Bptd;

class Provinsi extends Model
{
    use HasFactory, SoftDeletes, CrudBy;

    protected $table = 't_provinsi';
    protected $fillable = ['kode', 'nama', 'bptd_id', 'is_active'];
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

    public function bptd()
    {
        return $this->belongsTo(Bptd::class, 'bptd_id')->select(['id', 'kode', 'nama'])->where('is_active', true);
    }
}
