<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVrPelanggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vr_pelanggaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('tgl_pelanggaran')->nullable();
            $table->string('kd_pelanggaran', 50)->nullable();
            $table->string('no_ref')->nullable();
            $table->string('kode_uppkb', 30)->nullable();
            $table->integer('bptd_id')->nullable();
            $table->integer('regu_id')->nullable();
            $table->integer('shift_id')->nullable();
            $table->string('no_kendaraan', 30)->nullable();
            $table->string('no_uji', 30)->nullable();
            $table->date('tgl_uji')->nullable();
            $table->date('tgl_masa_berlaku')->nullable();
            $table->string('nama_pemilik')->nullable();
            $table->string('alamat_pemilik')->nullable();
            $table->integer('toleransi_komoditi')->nullable();
            $table->integer('toleransi_uppkb')->nullable();
            $table->float('berat_timbang')->nullable();
            $table->float('jbi_uji')->nullable();
            $table->float('kelebihan_berat')->nullable();
            $table->float('prosen_lebih')->nullable();
            $table->float('jbb_uji')->nullable();
            $table->float('jbkb_uji')->nullable();
            $table->float('mst_uji')->nullable();
            $table->integer('jenis_kendaraan_id')->nullable();
            $table->string('jenis_kendaraan')->nullable();
            $table->integer('sumbu_id')->nullable();
            $table->string('sumbu')->nullable();
            $table->integer('kategori_kepemilikan_id')->nullable();
            $table->integer('asal_kota_id')->nullable();
            $table->integer('tujuan_kota_id')->nullable();
            $table->string('asal_kode_kota', 30)->nullable();
            $table->string('tujuan_kode_kota', 30)->nullable();
            $table->boolean('is_gandengan')->default(0);
            $table->string('gandengan_no_uji', 30)->nullable();
            $table->string('gandengan_tgl_uji')->nullable();
            $table->string('gandengan_masa_berlaku')->nullable();
            $table->float('gandengan_jbi_uji')->nullable();
            $table->float('gandengan_jbki')->nullable();
            $table->integer('komoditi_id')->nullable();
            $table->string('pemilik_komoditi')->nullable();
            $table->string('alamat_pemilik_komoditi')->nullable();
            $table->string('no_surat_jalan')->nullable();
            $table->integer('device_id')->nullable(false);
            $table->integer('petugas_id')->nullable(false);
            $table->integer('lokasi_id')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->integer('verified_by')->nullable();
            $table->float('panjang_utama')->nullable();
            $table->float('panjang_toleransi')->nullable();
            $table->float('panjang_ukur')->nullable();
            $table->float('panjang_lebih')->nullable();
            $table->float('lebar_utama')->nullable();
            $table->float('lebar_toleransi')->nullable();
            $table->float('lebar_ukur')->nullable();
            $table->float('lebar_lebih')->nullable();
            $table->float('tinggi_utama')->nullable();
            $table->float('tinggi_toleransi')->nullable();
            $table->float('tinggi_ukur')->nullable();
            $table->float('tinggi_lebih')->nullable();
            $table->float('foh_utama')->nullable();
            $table->float('foh_toleransi')->nullable();
            $table->float('foh_ukur')->nullable();
            $table->float('foh_lebih')->nullable();
            $table->float('roh_utama')->nullable();
            $table->float('roh_toleransi')->nullable();
            $table->float('roh_ukur')->nullable();
            $table->float('roh_lebih')->nullable();
            $table->string('qrcode_name')->nullable();
            $table->string('qrcode_url')->nullable();
            $table->boolean('is_print')->default(0);
            $table->string('print_url')->nullable();
            $table->timestamp('tgl_capture')->nullable();
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
        Schema::dropIfExists('vr_pelanggaran');
    }
}
