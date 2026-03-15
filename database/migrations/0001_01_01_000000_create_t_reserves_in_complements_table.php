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
        Schema::create('_t_reserves_in_complements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reserves');
            $table->unsignedBigInteger('id_complements');
            $table->timestamps();

            /* Index */
            $table->index('id_reserves', 'idx_reserva_complement_reserva');
            $table->index('id_complements', 'idx_reserva_complement_complement');

        });

        /* TRIGGER */

        DB::unprepared('
        CREATE TRIGGER _t_reserves_in_complements_BEFORE_INSERT
        AFTER INSERT ON _t_reserves_in_complements
        FOR EACH ROW
        BEGIN
            INSERT INTO _t_reserves_in_complements_audit
            (id_reserves_in_complements, id_reserves, id_complements, tipo_audit, data_creacio_audit)
            VALUES
            (NEW.id, NEW.id_reserves, NEW.id_complements, "INSERT", NOW());
        END
        ');

        DB::unprepared('
        CREATE TRIGGER _t_reserves_in_complements_AFTER_UPDATE
        AFTER UPDATE ON _t_reserves_in_complements
        FOR EACH ROW
        BEGIN

            INSERT INTO _t_reserves_in_complements_audit
            (id_reserves_in_complements, id_reserves, id_complements, tipo_audit, data_creacio_audit)
            VALUES
            (OLD.id, OLD.id_reserves, OLD.id_complements, "UPDATE OLD", NOW());

            INSERT INTO _t_reserves_in_complements_audit
            (id_reserves_in_complements, id_reserves, id_complements, tipo_audit, data_creacio_audit)
            VALUES
            (NEW.id, NEW.id_reserves, NEW.id_complements, "UPDATE NEW", NOW());

        END
        ');

        DB::unprepared('
        CREATE TRIGGER _t_reserves_in_complements_AFTER_DELETE
        AFTER DELETE ON _t_reserves_in_complements
        FOR EACH ROW
        BEGIN

            INSERT INTO _t_reserves_in_complements_audit
            (id_reserves_in_complements, id_reserves, id_complements, tipo_audit, data_creacio_audit)
            VALUES
            (OLD.id, OLD.id_reserves, OLD.id_complements, "DELETE", NOW());

        END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS _t_reserves_in_complements_BEFORE_INSERT');
        DB::unprepared('DROP TRIGGER IF EXISTS _t_reserves_in_complements_AFTER_UPDATE');
        DB::unprepared('DROP TRIGGER IF EXISTS _t_reserves_in_complements_AFTER_DELETE');
        Schema::dropIfExists('_t_reserves_in_complements');
    }
};
