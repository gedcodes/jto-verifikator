<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVrDetailPelanggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vr_detail_pelanggaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pelanggaran_id')->nullable(false);
            $table->integer('jenis_pelanggaran_id')->nullable(false);
            $table->string('kode_pelanggaran')->nullable();
            $table->string('deskripsi')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vr_detail_pelanggaran');
    }
}
