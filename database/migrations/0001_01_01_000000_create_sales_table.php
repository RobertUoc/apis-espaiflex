<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
public function up(): void
    {

        Schema::create('_t_sales', function (Blueprint $table) {

            $table->id();
            $table->string('descripcio',200)->nullable();
            $table->unsignedBigInteger('id_edifici');
            $table->double('preu')->nullable();
            $table->string('actiu',2)->default('SI');
            $table->string('color',45)->nullable();
            $table->integer('max_ocupacio')->nullable();
            $table->string('missatge',150)->nullable();
            $table->unsignedBigInteger('horari');
            $table->longText('imatge')->nullable();
            $table->timestamps();

            /*  INDEXOS */
            $table->index('id_edifici');
            $table->index('horari');

            /* RELACIONS  */
            $table->foreign('id_edifici')
                ->references('id')
                ->on('_t_edificis')
                ->cascadeOnDelete();
            $table->foreign('horari')
                ->references('id')
                ->on('_t_hores');
        });

        /* TRIGGER */

        DB::unprepared('
        CREATE TRIGGER _t_sales_AFTER_DELETE
        AFTER DELETE ON _t_sales
        FOR EACH ROW
        BEGIN
            DELETE FROM _t_sales_in_complements
            WHERE id_sales = OLD.id
            AND id > 0;
        END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS _t_sales_AFTER_DELETE');
        Schema::dropIfExists('_t_sales');
    }    
};
