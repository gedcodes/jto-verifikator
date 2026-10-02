<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVrDeteksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vr_deteksi', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('tgl_deteksi')->nullable();
            $table->integer('jml_deteksi')->nullable();
            $table->integer('lokasi_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vr_deteksi');
    }
}
