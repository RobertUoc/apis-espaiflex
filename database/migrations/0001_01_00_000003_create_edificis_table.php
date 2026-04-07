<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('_t_edificis', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 200);
                $table->unsignedBigInteger('id_provincia');
                $table->longText('imatge')->nullable();
                $table->text('descripcio');
                $table->enum('actiu', ['SI', 'NO'])->default('SI');            
                $table->decimal('latitud', 10, 7);
                $table->decimal('longitud', 10, 7);                                
                $table->timestamps();
                
                // Índice
                $table->index('id_provincia');

                $table->foreign('id_provincia')
                    ->references('id')
                    ->on('_t_provincies')
                    ->onDelete('restrict');

            });        


            /* Trigger */
            DB::unprepared('
                CREATE TRIGGER trg_delete_edifici_sales
                    AFTER DELETE ON _t_edificis
                    FOR EACH ROW
                        BEGIN
                            DELETE FROM _t_sales
                            WHERE id_edifici = OLD.id
                            AND id > 0;
                        END
            ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_delete_edifici_sales');
        Schema::dropIfExists('_t_edificis');
    }
};
