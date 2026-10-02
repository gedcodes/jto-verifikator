<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeWidget extends Model
{
    use HasFactory;

    protected $table = 'v_resume_widget';
    protected $fillable = ['jml_users', 'jml_vendor', 'jml_lokasi', 'jml_device'];
}
