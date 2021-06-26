<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class UserTable extends BaseTableMigration
{
    public function up()
    {
        parent::up();

        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            "user_uid" => [
                "type" => "CHAR",
                "constraint" => 36,
                'null' => false,
            ],

            "worker_uid" => [
                "type" => "CHAR",
                "constraint" => 36,
                'null' => true,
            ],

            'user_email'       => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'user_password'       => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null' => false,
            ],

            'user_password_open'       => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null' => false,
            ],

            'user_description' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => 'descrição'
            ],



        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('user_uid', false, true);
        $this->forge->addKey('user_email', false, true);
        $this->forge->createTable('user');
    }

    public function down()
    {
        $this->forge->dropTable('user', true);
    }
}
