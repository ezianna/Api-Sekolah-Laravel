<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('gurus', function (Blueprint $table) {
        $table->id();
        $table->string('nip')->unique();
        $table->string('nama');
        $table->string('tempat_lahir')->nullable();
        $table->date('tgl_lahir')->nullable();
        $table->string('gender')->nullable();
        $table->string('phone_number')->nullable();
        $table->string('email')->unique();
        $table->text('alamat')->nullable();
        $table->string('pendidikan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
