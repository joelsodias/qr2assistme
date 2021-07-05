<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class CustomerUsersTable extends BaseTableMigration
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

            'customer_uid'          => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],

            'user_uid'          => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],



        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('customer_uid', true);
        $this->forge->addKey('user_uid', true);
        //$this->forge->addForeignKey('customer_uid', 'customer', 'customer_uid');
        //$this->forge->addForeignKey('user_uid', 'user', 'user_uid');
        $this->forge->createTable('customer_users');
    }

    public function down()
    {
        $this->forge->dropTable('customer_users', true);
    }
}
