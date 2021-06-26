<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class QrObjectTable extends BaseTableMigration
{
    public function up()
    {
        parent::up();

        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "auto_increment" => true,
                "unsigned" => true,
                "constraint" => 11
            ],
            "object_uid" => [
                "type" => "CHAR",
                "constraint"     => 36,
                "null" => false
            ],
            'owner_uid'          => [
                'type'           => 'CHAR',
                "constraint"     => 36,
                'null'       => true,
            ],
            "object_name" => [
                "type" => "VARCHAR",
                "constraint"     => 250,
                "null" => true
            ],
            "description" => [
                "type" => "TEXT",
                "null" => true
            ],
            "object_serial" => [
                "type" => "VARCHAR",
                "null" => true,
                "constraint"     => 250,
            ],
            "object_model" => [
                "type" => "VARCHAR",
                "null" => true,
                "constraint"     => 250,
            ],
            "object_voltage" => [
                "type" => "VARCHAR",
                "null" => true,
                "constraint"     => 250,
            ],
            "obs" => [
                "type" => "TEXT",
                "null" => true
            ],
            

        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->addKey("object_uid",false,true);
        $this->forge->addKey("owner_uid");
        $this->forge->createTable("qrobject");
    }

    public function down()
    {
        $this->forge->dropTable("qrobject", true);
    }
}
