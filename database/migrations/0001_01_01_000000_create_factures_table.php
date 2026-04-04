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
        Schema::create('_t_factures', function (Blueprint $table) {           
            $table->id();
            $table->unsignedBigInteger('id_reserva')->nullable();            
            $table->date('data_factura');
            $table->integer('dias')->default(0);
            $table->decimal('precio_dia', 10, 2)->default(0);
            $table->decimal('base', 10, 2)->default(0);
            $table->decimal('iva', 10, 2)->default(0);
            $table->decimal('iva_import', 10, 2)->default(0);
            $table->decimal('total_factura', 10, 2)->default(0);
            $table->unsignedBigInteger('enviada')->default(0);
            $table->timestamps();

            $table->index('id_reserva');
            $table->index('data_factura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_t_factures');
    }
};
