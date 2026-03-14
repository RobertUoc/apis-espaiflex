<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('_t_hores')->insert([
            ['id'=>1,'tipus'=>1,'hora'=>'00:00:00','activa'=>'NO'],
            ['id'=>2,'tipus'=>1,'hora'=>'01:00:00','activa'=>'NO'],
            ['id'=>3,'tipus'=>1,'hora'=>'02:00:00','activa'=>'NO'],
            ['id'=>4,'tipus'=>1,'hora'=>'03:00:00','activa'=>'NO'],
            ['id'=>5,'tipus'=>1,'hora'=>'04:00:00','activa'=>'NO'],
            ['id'=>6,'tipus'=>1,'hora'=>'05:00:00','activa'=>'NO'],
            ['id'=>7,'tipus'=>1,'hora'=>'06:00:00','activa'=>'SI'],
            ['id'=>8,'tipus'=>1,'hora'=>'07:00:00','activa'=>'SI'],
            ['id'=>9,'tipus'=>1,'hora'=>'08:00:00','activa'=>'SI'],
            ['id'=>10,'tipus'=>1,'hora'=>'09:00:00','activa'=>'SI'],
            ['id'=>11,'tipus'=>1,'hora'=>'10:00:00','activa'=>'SI'],
            ['id'=>12,'tipus'=>1,'hora'=>'11:00:00','activa'=>'SI'],
            ['id'=>13,'tipus'=>1,'hora'=>'12:00:00','activa'=>'SI'],
            ['id'=>14,'tipus'=>1,'hora'=>'13:00:00','activa'=>'SI'],
            ['id'=>15,'tipus'=>1,'hora'=>'14:00:00','activa'=>'SI'],
            ['id'=>16,'tipus'=>1,'hora'=>'15:00:00','activa'=>'SI'],
            ['id'=>17,'tipus'=>1,'hora'=>'16:00:00','activa'=>'SI'],
            ['id'=>18,'tipus'=>1,'hora'=>'17:00:00','activa'=>'SI'],
            ['id'=>19,'tipus'=>1,'hora'=>'18:00:00','activa'=>'SI'],
            ['id'=>20,'tipus'=>1,'hora'=>'19:00:00','activa'=>'SI'],
            ['id'=>21,'tipus'=>1,'hora'=>'20:00:00','activa'=>'SI'],
            ['id'=>22,'tipus'=>1,'hora'=>'21:00:00','activa'=>'NO'],
            ['id'=>23,'tipus'=>1,'hora'=>'22:00:00','activa'=>'NO'],
            ['id'=>24,'tipus'=>1,'hora'=>'23:00:00','activa'=>'NO'],
            ['id'=>25,'tipus'=>2,'hora'=>'00:00:00','activa'=>'NO'],
            ['id'=>26,'tipus'=>2,'hora'=>'00:30:00','activa'=>'NO'],
            ['id'=>27,'tipus'=>2,'hora'=>'01:00:00','activa'=>'NO'],
            ['id'=>28,'tipus'=>2,'hora'=>'01:30:00','activa'=>'NO'],
            ['id'=>29,'tipus'=>2,'hora'=>'02:00:00','activa'=>'NO'],
            ['id'=>30,'tipus'=>2,'hora'=>'02:30:00','activa'=>'NO'],
            ['id'=>31,'tipus'=>2,'hora'=>'03:00:00','activa'=>'NO'],
            ['id'=>32,'tipus'=>2,'hora'=>'03:30:00','activa'=>'NO'],
            ['id'=>33,'tipus'=>2,'hora'=>'04:00:00','activa'=>'NO'],
            ['id'=>34,'tipus'=>2,'hora'=>'04:30:00','activa'=>'NO'],
            ['id'=>35,'tipus'=>2,'hora'=>'05:00:00','activa'=>'NO'],
            ['id'=>36,'tipus'=>2,'hora'=>'05:30:00','activa'=>'NO'],
            ['id'=>37,'tipus'=>2,'hora'=>'06:00:00','activa'=>'SI'],
            ['id'=>38,'tipus'=>2,'hora'=>'06:30:00','activa'=>'SI'],
            ['id'=>39,'tipus'=>2,'hora'=>'07:00:00','activa'=>'SI'],
            ['id'=>40,'tipus'=>2,'hora'=>'07:30:00','activa'=>'SI'],
            ['id'=>41,'tipus'=>2,'hora'=>'08:00:00','activa'=>'SI'],
            ['id'=>42,'tipus'=>2,'hora'=>'08:30:00','activa'=>'SI'],
            ['id'=>43,'tipus'=>2,'hora'=>'09:00:00','activa'=>'SI'],
            ['id'=>44,'tipus'=>2,'hora'=>'09:30:00','activa'=>'SI'],
            ['id'=>45,'tipus'=>2,'hora'=>'10:00:00','activa'=>'SI'],
            ['id'=>46,'tipus'=>2,'hora'=>'10:30:00','activa'=>'SI'],
            ['id'=>47,'tipus'=>2,'hora'=>'11:00:00','activa'=>'SI'],
            ['id'=>48,'tipus'=>2,'hora'=>'11:30:00','activa'=>'SI'],
            ['id'=>49,'tipus'=>2,'hora'=>'12:00:00','activa'=>'SI'],
            ['id'=>50,'tipus'=>2,'hora'=>'12:30:00','activa'=>'SI'],
            ['id'=>51,'tipus'=>2,'hora'=>'13:00:00','activa'=>'SI'],
            ['id'=>52,'tipus'=>2,'hora'=>'13:30:00','activa'=>'SI'],
            ['id'=>53,'tipus'=>2,'hora'=>'14:00:00','activa'=>'SI'],
            ['id'=>54,'tipus'=>2,'hora'=>'14:30:00','activa'=>'SI'],
            ['id'=>55,'tipus'=>2,'hora'=>'15:00:00','activa'=>'SI'],
            ['id'=>56,'tipus'=>2,'hora'=>'15:30:00','activa'=>'SI'],
            ['id'=>57,'tipus'=>2,'hora'=>'16:00:00','activa'=>'SI'],
            ['id'=>58,'tipus'=>2,'hora'=>'16:30:00','activa'=>'SI'],
            ['id'=>59,'tipus'=>2,'hora'=>'17:00:00','activa'=>'SI'],
            ['id'=>60,'tipus'=>2,'hora'=>'17:30:00','activa'=>'SI'],
            ['id'=>61,'tipus'=>2,'hora'=>'18:00:00','activa'=>'SI'],
            ['id'=>62,'tipus'=>2,'hora'=>'18:30:00','activa'=>'SI'],
            ['id'=>63,'tipus'=>2,'hora'=>'19:00:00','activa'=>'SI'],
            ['id'=>64,'tipus'=>2,'hora'=>'19:30:00','activa'=>'SI'],
            ['id'=>65,'tipus'=>2,'hora'=>'20:00:00','activa'=>'SI'],
            ['id'=>66,'tipus'=>2,'hora'=>'20:30:00','activa'=>'SI'],
            ['id'=>67,'tipus'=>2,'hora'=>'21:00:00','activa'=>'SI'],
            ['id'=>68,'tipus'=>2,'hora'=>'21:30:00','activa'=>'SI'],
            ['id'=>69,'tipus'=>2,'hora'=>'22:00:00','activa'=>'NO'],
            ['id'=>70,'tipus'=>2,'hora'=>'22:30:00','activa'=>'NO'],
            ['id'=>71,'tipus'=>2,'hora'=>'23:00:00','activa'=>'NO'],
            ['id'=>72,'tipus'=>2,'hora'=>'23:30:00','activa'=>'NO'],
        ]);
    }
}
