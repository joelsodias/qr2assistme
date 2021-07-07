<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;


class ChatUserTable extends BaseTableMigration
{
    public function up()
    {
        parent::up();

        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "unsigned" => true,
                "constraint" => 11,
                "auto_increment" => true,
            ],

            "chat_user_uid" => [
                "type" => "CHAR",
                "constraint" => 36,
                "null" => false,
            ],

            "user_type" => [
                "type" => "ENUM('attendant','attendee')",
                "null" => false,
            ],

            "user_uid" => [
                "type" => "CHAR",
                "constraint" => 36,
                "null" => true,
            ],

            "google_token" => [
                "type" => "TEXT",
                "null" => true,
            ],

            "google_email" => [
                "type" => "VARCHAR",
                "constraint" => 250,
                "null" => true,
            ],

            "google_avatar" => [
                "type" => "VARCHAR",
                "constraint" => 250,
                "null" => true,
            ],

            "google_name" => [
                "type" => "VARCHAR",
                "constraint" => 250,
                "null" => true,
            ],

            "facebook_token" => [
                "type" => "TEXT",
                "null" => true,
            ],

            "facebook_email" => [
                "type" => "VARCHAR",
                "constraint" => 250,
                "null" => true,
            ],

            "facebook_avatar" => [
                "type" => "VARCHAR",
                "constraint" => 250,
                "null" => true,
            ],

            "facebook_name" => [
                "type" => "VARCHAR",
                "constraint" => 250,
                "null" => true,
            ],

            "user_name" => [
                "type" => "VARCHAR",
                "constraint" => 250,
                "null" => true,
            ],

            "user_email" => [
                "type" => "VARCHAR",
                "constraint" => 250,
                "null" => true,
            ],

            "user_avatar" => [
                "type" => "VARCHAR",
                "constraint" => 250,
                "null" => true,
            ],

            "is_guest" => [
                "type" => "ENUM('S','N')",
                "null" => false,
                "default" => "N"
            ],


        ]);

        $this->forge->addPrimaryKey("id");
        $this->forge->addKey("chat_user_uid", false, true);
        $this->forge->addKey("google_oauth_uid");
        $this->forge->addKey("facebook_oauth_uid");

        $this->forge->createTable("chat_user");
    }

    public function down()
    {
        $this->forge->dropTable("chat_user", true);
    }
}
