<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVrDetailCaptureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vr_detail_capture', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pelanggaran_id')->nullable(false);
            $table->uuid('jt_vr_data_id')->nullable();
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
            $table->timestamp('tgl_capture')->nullable();
            $table->boolean('is_plat')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('device_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vr_detail_capture');
    }
}
