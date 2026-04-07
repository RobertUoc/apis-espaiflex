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
        Schema::create('_t_complements', function (Blueprint $table) {
                $table->id();
                $table->string('descripcio', 200);
                $table->decimal('preu', 8, 2);
                $table->string('actiu', 2);
                $table->timestamps();
            });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_t_complements');
    }
};
