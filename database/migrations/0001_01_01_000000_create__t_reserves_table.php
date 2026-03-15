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
        Schema::create('_t_reserves', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sala');
                $table->date('dia_inici')->nullable();
                $table->date('dia_fi')->nullable();
                $table->time('hora_inici')->nullable();
                $table->time('hora_fi')->nullable();
                $table->string('frequencia',10);
                $table->integer('dilluns')->default(0);
                $table->integer('dimarts')->default(0);
                $table->integer('dimecres')->default(0);
                $table->integer('dijous')->default(0);
                $table->integer('divendres')->default(0);
                $table->integer('dissabte')->default(0);
                $table->integer('diumenge')->default(0);
                $table->integer('tipo')->nullable();
                $table->integer('dia_mes')->nullable();
                $table->integer('el_semana')->nullable();
                $table->integer('el_dia')->nullable();
                $table->double('import')->nullable();
                $table->string('actiu',2)->default('SI');
                $table->unsignedBigInteger('id_user');
                $table->dateTime('data_creacio')->nullable();
                $table->dateTime('data_update')->nullable();
                $table->dateTime('data_delete')->nullable();
                $table->timestamps();

                /* INDICES */
                $table->index(['sala','dia_inici','dia_fi','hora_inici','hora_fi'], 'idx_reserva_sala_dia');
                $table->index('id_user', 'idx_reserva_user');
                
            });       

            /*  TRIGGER   */

            DB::unprepared('
            CREATE TRIGGER _t_reserves_BEFORE_INSERT
            BEFORE INSERT ON _t_reserves
            FOR EACH ROW
            BEGIN
                SET NEW.data_creacio = NOW();
                SET NEW.data_update = NOW();
            END
            ');

            DB::unprepared('
            CREATE TRIGGER _t_reserves_AFTER_INSERT
            AFTER INSERT ON _t_reserves
            FOR EACH ROW
            BEGIN

            INSERT INTO _t_reserves_audit
            (
            id_reserva, sala, dia_inici, dia_fi, hora_inici, hora_fi, frequencia, dilluns, dimarts, dimecres, dijous, divendres, dissabte, diumenge,
            dia_mes, tipo, el_semana, el_dia, import, actiu, id_user, data_creacio, data_update, data_delete, tipo_audit, data_creacio_audit
            )
            VALUES
            (
            NEW.id, NEW.sala, NEW.dia_inici, NEW.dia_fi, NEW.hora_inici, NEW.hora_fi, NEW.frequencia, NEW.dilluns,
            NEW.dimarts, NEW.dimecres, NEW.dijous, NEW.divendres, NEW.dissabte, NEW.diumenge, NEW.dia_mes, NEW.tipo,
            NEW.el_semana, NEW.el_dia, NEW.import, NEW.actiu, NEW.id_user, NEW.data_creacio, NEW.data_update, NEW.data_delete, "INSERT", NOW()
            );

            END
            ');

            DB::unprepared('
            CREATE TRIGGER _t_reserves_BEFORE_UPDATE
            BEFORE UPDATE ON _t_reserves
            FOR EACH ROW
            BEGIN

                SET NEW.data_update = NOW();

                INSERT INTO _t_reserves_audit
                (id_reserva, sala, dia_inici, dia_fi, hora_inici, hora_fi, frequencia,
                dilluns, dimarts, dimecres, dijous, divendres, dissabte, diumenge,
                dia_mes, tipo, el_semana, el_dia, import, actiu, id_user,
                data_creacio, data_update, data_delete, tipo_audit, data_creacio_audit)

                VALUES
                (OLD.id, OLD.sala, OLD.dia_inici, OLD.dia_fi, OLD.hora_inici, OLD.hora_fi,
                OLD.frequencia, OLD.dilluns, OLD.dimarts, OLD.dimecres, OLD.dijous,
                OLD.divendres, OLD.dissabte, OLD.diumenge, OLD.dia_mes, OLD.tipo,
                OLD.el_semana, OLD.el_dia, OLD.import, OLD.actiu, OLD.id_user,
                OLD.data_creacio, OLD.data_update, OLD.data_delete, "UPDATE OLD", NOW());

                INSERT INTO _t_reserves_audit
                (id_reserva, sala, dia_inici, dia_fi, hora_inici, hora_fi, frequencia,
                dilluns, dimarts, dimecres, dijous, divendres, dissabte, diumenge,
                dia_mes, tipo, el_semana, el_dia, import, actiu, id_user,
                data_creacio, data_update, data_delete, tipo_audit, data_creacio_audit)

                VALUES
                (NEW.id, NEW.sala, NEW.dia_inici, NEW.dia_fi, NEW.hora_inici, NEW.hora_fi,
                NEW.frequencia, NEW.dilluns, NEW.dimarts, NEW.dimecres, NEW.dijous,
                NEW.divendres, NEW.dissabte, NEW.diumenge, NEW.dia_mes, NEW.tipo,
                NEW.el_semana, NEW.el_dia, NEW.import, NEW.actiu, NEW.id_user,
                NEW.data_creacio, NEW.data_update, NEW.data_delete, "UPDATE NEW", NOW());

            END
            ');

            DB::unprepared('
            CREATE TRIGGER _t_reserves_AFTER_DELETE
            AFTER DELETE ON _t_reserves
            FOR EACH ROW
            BEGIN

                DELETE FROM _t_reserves_in_complements
                WHERE id_reserves = OLD.id AND id > 0;

                DELETE FROM _t_reserves_comentaris
                WHERE id_reserves = OLD.id AND id > 0;

                INSERT INTO _t_reserves_audit
                (id_reserva, sala, dia_inici, dia_fi, hora_inici, hora_fi, frequencia,
                dilluns, dimarts, dimecres, dijous, divendres, dissabte, diumenge,
                dia_mes, tipo, el_semana, el_dia, import, actiu, id_user,
                data_creacio, data_update, data_delete, tipo_audit, data_creacio_audit)

                VALUES
                (OLD.id, OLD.sala, OLD.dia_inici, OLD.dia_fi, OLD.hora_inici, OLD.hora_fi,
                OLD.frequencia, OLD.dilluns, OLD.dimarts, OLD.dimecres, OLD.dijous,
                OLD.divendres, OLD.dissabte, OLD.diumenge, OLD.dia_mes, OLD.tipo,
                OLD.el_semana, OLD.el_dia, OLD.import, OLD.actiu, OLD.id_user,
                OLD.data_creacio, OLD.data_update, OLD.data_delete, "DELETE", NOW());

            END
            ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS _t_reserves_BEFORE_INSERT');
        DB::unprepared('DROP TRIGGER IF EXISTS _t_reserves_BEFORE_UPDATE');
        DB::unprepared('DROP TRIGGER IF EXISTS _t_reserves_AFTER_DELETE');

        Schema::dropIfExists('_t_reservas');
    }
};
