<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class TicketTable extends BaseTableMigration
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
            "ticket_uid" => [
                "type" => "CHAR",
                "constraint" => 36,
                "null" => false
            ],
            "parent_uid" => [
                "type" => "CHAR",
                "constraint" => 36,
                "null" => true
            ],
            'owner_uid'          => [
                'type'           => 'CHAR',
                "constraint"     => 36,
                'null'       => true,
            ],
            'executor_uid'          => [
                'type'           => 'CHAR',
                "constraint"     => 36,
                'null'       => true,
            ],
            "description" => [
                "type" => "TEXT",
                "null" => true
            ],
            "ticket_type" => [
                "type" => "ENUM('service','task')",
                "null" => true,
            ],
            "ticket_status" => [
                "type" => "ENUM('draft','open','closed','canceled')",
                "null" => true,
                "default" => "draft"
            ],
            'open_at'       => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
            ],
            

        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->addKey("ticket_uid",false,true);
        $this->forge->addKey("owner_uid");
        $this->forge->addKey("parent_uid");
        $this->forge->addKey("executor_uid");
        $this->forge->addKey("ticket_status");
        $this->forge->createTable("ticket");
    }

    public function down()
    {
        $this->forge->dropTable("ticket", true);
    }
}
