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
 Schema::create('_t_reserves_comentaris', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reserves');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('nom', 150);
            $table->text('comentari')->nullable();
            $table->integer('puntuacio')->nullable();
            $table->timestamps();

            /* INDEX */
            $table->index('id_user', 'idx_comentari_user');        
            $table->index('id_reserves', 'idx_comentari_reserva');
            $table->index(['id_reserves','created_at'], 'idx_comentari_reserva_data');            
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_t_reserves_comentaris');
    }
};
