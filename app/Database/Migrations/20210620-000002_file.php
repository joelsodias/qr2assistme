<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class FileTable extends BaseTableMigration
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

            'file_uid'          => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                "null"              => false
            ],

            'worker_uid'         => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                'null'              => true,
            ],

            'schedule_uid'         => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                'null'              => true,
            ],

            'object_uid'         => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                'null'              => true,
            ],

            'ticket_uid'         => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                'null'              => true,
            ],

            'customer_uid'         => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                'null'              => true,
            ],

            'file_deleted_by_uid'         => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                'null'              => true,
            ],
            
            'file_status'         => [
                'type'              => 'ENUM("draft","active","deleted")',
                'deafult'           => 'draft',
                'null'              => false,
            ],
            
            'file_context' => [
                'type'              => 'ENUM("object","other")',
                'null'              => true,
            ],
            
            'file_name' => [
                'type'              => 'VARCHAR',
                'constraint'        => 250,
                'null'              => true,
            ],

            'file_folder' => [
                'type'              => 'VARCHAR',
                'constraint'        => 250,
                'null'              => true,
            ],
           
            'file_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],


        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey("file_uid", false, true);
        $this->forge->addKey("worker_uid");
        $this->forge->addKey("object_uid");
        $this->forge->addKey("customer_uid");
        $this->forge->addKey("ticket_uid");
        $this->forge->addKey("file_status");
        $this->forge->createTable('file');
    }

    public function down()
    {
        $this->forge->dropTable('file', true);
    }
}
