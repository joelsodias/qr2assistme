<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class UserAccessTable extends BaseTableMigration
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

            'grant_key'       => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null' => false,
            ],

            'permission'       => [
                'type'       => 'ENUM("deny","allow")',
                'null' => false,
            ],

        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('user_uid');
        $this->forge->addKey(['user_uid','grant_key'],false,true);
        $this->forge->createTable('user_access');
    }

    public function down()
    {
        $this->forge->dropTable('user_access', true);
    }
}
