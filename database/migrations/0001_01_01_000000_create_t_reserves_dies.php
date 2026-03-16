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
        Schema::create('_t_reserves_dies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_reserva');
            $table->date('dia_inici');
            $table->date('dia_fi');
            $table->time('hora_inici');
            $table->time('hora_fi');
            $table->enum('actiu', ['SI','NO'])->default('SI');
            $table->timestamp('data_delete')->nullable();
            $table->timestamps();

            // índices
            $table->index('id_reserva');
            $table->index(['dia_inici','hora_inici']);
            $table->index(['dia_fi','hora_fi']);

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_t_reserves_dies');
    }
};
