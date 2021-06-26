<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class CustomerTable extends BaseTableMigration
{
    public function up()
    {
        parent::up();

        $this->forge->addField([

            'id'           => [
                'type'              => 'INT',
                'constraint'        => 11,
                'unsigned'          => true,
                'auto_increment'    => true,
            ],

            'customer_uid'          => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                "null"              => false
            ],

            'customer_name'         => [
                'type'              => 'VARCHAR',
                'constraint'        => 250,
                'unique'            => true,
                'null'              => false,
            ],

            'customer_email'       => [
                'type'       => 'VARCHAR',
                'constraint' => 250,

            ],
            'customer_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'customer_admin_user_uid' => [
                'type' => 'CHAR',
                'constraint'     => 36,
                'null' => true,
            ],


        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey("customer_uid", false, true);
        $this->forge->addKey("customer_admin_user_uid");
        $this->forge->createTable('customer');
    }

    public function down()
    {
        $this->forge->dropTable('customer', true);
    }
}
