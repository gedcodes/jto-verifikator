<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CrudBy;

class Kotakab extends Model
{
    use HasFactory, SoftDeletes, CrudBy;

    protected $table = 't_kota_kab';
    protected $fillable = ['ukode', 'kode', 'nama', 'provinsi_id', 'is_active'];
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

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id')->select(['id', 'kode', 'nama', 'bptd_id'])->where('is_active', true)->with(['bptd']);
    }
}
