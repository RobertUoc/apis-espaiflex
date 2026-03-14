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
        Schema::create('_t_hores', function (Blueprint $table) {
            $table->id();
            $table->integer('tipus');       // 1 = hora, 2 = mitja hora
            $table->time('hora');
            $table->string('activa',2);     // SI / NO
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_t_hores');
    }
};
