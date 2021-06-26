<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class ChatMessageTable extends BaseTableMigration
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

            "message_uid" => [
                "type" => "CHAR",
                "constraint" => 36,
                "null" => false,
            ],

            "session_uid" => [
                "type" => "CHAR",
                "constraint" => 36,
                "null" => false,
            ],            

            "sender_uid" => [
                "type" => "CHAR",
                "null" => false,
                "constraint" => 36,
            ],

            "message" => [
                "type" => "TEXT",
                "null" => false
            ],
            
            "sync_status" => [
                "type" => "ENUM('stored','confirmed','read','done')",
                "null" => false
            ],

            'confirmed_at'       => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
            ],

            'delivered_at'       => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
            ],

            'read_at'       => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
            ],


        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->addKey("message_uid",false,true);
        $this->forge->addKey("session_uid");
        $this->forge->addKey("sender_uid");
        $this->forge->createTable("chat_message");
    }

    public function down()
    {
        $this->forge->dropTable("chat_message", true);
    }
}
