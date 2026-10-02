<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJtVrDataArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jt_vr_data_archive', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('jt_vr_data_id')->nullable(false);
            $table->timestamp('tgl_capture')->nullable();
            $table->string('no_kendaraan', 30)->nullable();
            $table->string('img_name')->nullable();
            $table->string('img2_name')->nullable();
            $table->string('img3_name')->nullable();
            $table->string('img4_name')->nullable();
            $table->string('img_plat_depan_name')->nullable();
            $table->string('img_plat_belakang_name')->nullable();
            $table->string('img_url')->nullable();
            $table->string('img2_url')->nullable();
            $table->string('img3_url')->nullable();
            $table->string('img4_url')->nullable();
            $table->string('img_plat_depan_url')->nullable();
            $table->string('img_plat_belakang_url')->nullable();
            $table->integer('device_id')->nullable();
            $table->boolean('is_verifikasi')->default(0);
            $table->float('berat_timbang')->nullable();
            $table->float('panjang_ukur')->nullable();
            $table->float('lebar_ukur')->nullable();
            $table->float('tinggi_ukur')->nullable();
            $table->float('foh_ukur')->nullable();
            $table->float('roh_ukur')->nullable();
            $table->boolean('is_plat')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->string('kd_referensi')->nullable();
            $table->string('id_referensi')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('keterangan_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jt_vr_data_archive');
    }
}
