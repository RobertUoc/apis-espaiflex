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
        Schema::create('_t_reserves_audit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reserva')->nullable();
            $table->unsignedBigInteger('sala')->nullable();
            $table->date('dia_inici')->nullable();
            $table->date('dia_fi')->nullable();
            $table->string('frequencia',10);
            $table->integer('dilluns')->nullable();
            $table->integer('dimarts')->nullable();
            $table->integer('dimecres')->nullable();
            $table->integer('dijous')->nullable();
            $table->integer('divendres')->nullable();
            $table->integer('dissabte')->nullable();
            $table->integer('diumenge')->nullable();
            $table->integer('dia_mes')->nullable();
            $table->integer('tipo')->nullable();
            $table->integer('el_semana')->nullable();
            $table->integer('el_dia')->nullable();
            $table->double('import')->nullable();
            $table->string('actiu',2);
            $table->unsignedBigInteger('id_user')->nullable();
            $table->dateTime('data_creacio')->nullable();
            $table->dateTime('data_update')->nullable();
            $table->dateTime('data_delete')->nullable();
            $table->string('tipo_audit',50);
            $table->dateTime('data_creacio_audit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_t_reserves_audit');
    }
};
