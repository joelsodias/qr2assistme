<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class WorkerTable extends BaseTableMigration
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

            'worker_uid'          => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                "null"              => false
            ],

            'worker_type' => [
                'type' => 'ENUM("attendant","field")',
                'null' => false,
            ],

            'worker_name'         => [
                'type'              => 'VARCHAR',
                'constraint'        => 250,
                'unique'            => true,
                'null'              => false,
            ],

            'worker_email'       => [
                'type'       => 'VARCHAR',
                'constraint' => 250,

            ],

            'worker_avatar'         => [
                'type'              => 'VARCHAR',
                'constraint'        => 250,
                'null'              => true,
            ],

            'worker_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],


        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey("worker_uid", false, true);
        $this->forge->createTable('worker');
    }

    public function down()
    {
        $this->forge->dropTable('worker', true);
    }
}
