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
            'object_owner_uid'          => [
                'type'           => 'CHAR',
                "constraint"     => 36,
                'null'       => true,
            ],
            "object_name" => [
                "type" => "VARCHAR",
                "constraint"     => 250,
                "null" => true
            ],
            "object_description" => [
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
            "object_brand" => [
                "type" => "VARCHAR",
                "null" => true,
                "constraint"     => 250,
            ],
            "object_voltage" => [
                "type" => "VARCHAR",
                "null" => true,
                "constraint"     => 250,
            ],
            "object_obs" => [
                "type" => "TEXT",
                "null" => true
            ],
            "object_instalation_date" => [
                "type" => "TIMESTAMP",
                "null" => true,
            ],
            "object_last_review_date" => [
                "type" => "TIMESTAMP",
                "null" => true,
            ],
            "object_next_review_date" => [
                "type" => "TIMESTAMP",
                "null" => true,
            ],
            "object_maker_warranty_exp_date" => [
                "type" => "TIMESTAMP",
                "null" => true,
            ],
        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->addKey("object_uid",false,true);
        $this->forge->addKey("object_owner_uid");
        $this->forge->createTable("qrobject");
    }

    public function down()
    {
        $this->forge->dropTable("qrobject", true);
    }
}
