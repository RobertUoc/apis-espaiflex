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
        Schema::create('_t_reserves_in_complements_audit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reserves_in_complements')->nullable();
            $table->unsignedBigInteger('id_reserves')->nullable();
            $table->unsignedBigInteger('id_complements')->nullable();
            $table->string('tipo_audit',50);
            $table->timestamps();
            $table->dateTime('data_creacio_audit');
        });
            
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_t_reserves_in_complements_audit');
    }
};
