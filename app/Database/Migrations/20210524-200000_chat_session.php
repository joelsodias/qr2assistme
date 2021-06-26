<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class ChatSessionTable extends BaseTableMigration
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

            "session_uid" => [
                "type" => "CHAR",
                "null" => false,
                "constraint" => 36
            ],

            "attendee_uid" => [
                "type" => "CHAR",
                "null" => false,
                "constraint" => 36
            ],

            "attendant_uid" => [
                "type" => "CHAR",
                "null" => true,
                "constraint" => 36
            ],

            "session_status" => [
                "type" => "ENUM('new','open','closed')",
                "null" => true,
                "default" => "new"
            ],



        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->addKey("session_uid",false,true);
        $this->forge->addKey("attendant_uid");
        $this->forge->addKey("attendee_uid");
        $this->forge->addKey("session_status");
        $this->forge->createTable("chat_session");
    }

    public function down()
    {
        $this->forge->dropTable("chat_session", true);
    }
}
