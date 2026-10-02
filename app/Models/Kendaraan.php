<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'jt_kendaraan';
    protected $fillable = ['no_reg_kend', 'no_uji', 'tanggal_uji', 'masa_berlaku_uji', 'nama_pemilik', 'alamat_pemilik', 'jbi', 'mst', 'jenis_kendaraan_id', 'jenis_kend', 'sumbu_id', 'konfigurasi_sumbu', 'kepemilikan_id'];
}
