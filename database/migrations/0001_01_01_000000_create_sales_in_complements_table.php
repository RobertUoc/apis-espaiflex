<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('_t_sales_in_complements', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('id_sales')->index();
            $table->unsignedBigInteger('id_complements')->index();
            $table->timestamps(false);

            /* Index */
            $table->index('id_sales', 'idx_sales');
            $table->index('id_complements', 'idx_complements');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('_t_sales_in_complements');
    }
};