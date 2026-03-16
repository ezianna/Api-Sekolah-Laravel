<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('guru', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable();
        $table->string('nip');
        $table->string('nama');
        $table->string('tempat_lahir');
        $table->date('tgl_lahir');
        $table->string('gender');
        $table->string('phone_number');
        $table->string('email');
        $table->text('alamat');
        $table->string('pendidikan');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
