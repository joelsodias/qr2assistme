<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class EventTable extends BaseTableMigration
{
    public function up()
    {
        parent::up();

        $this->forge->addField([

            'id'            => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'event_uid'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,

            ],

            'customer_uid'          => [
                'type'           => 'CHAR',
                'constraint'     => 36,
                'null'          => true,

            ],

            'worker_uid'          => [
                'type'           => 'CHAR',
                'constraint'     => 36,
                'null'          => true,

            ],

            'login_uid'          => [
                'type'           => 'CHAR',
                'constraint'     => 36,
                'null'          => true,

            ],

            'appointment_uid'          => [
                'type'           => 'CHAR',
                'constraint'     => 36,
                'null'          => true,

            ],

            'object_uid'          => [
                'type'           => 'CHAR',
                'constraint'     => 36,
                'null'          => true,

            ],

            'event_log'          => [
                'type'           => 'TEXT',
                'null'           => true,

            ],




        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('event_uid', false, true);
        $this->forge->addKey('login_uid');
        $this->forge->addKey('worker_uid');
        $this->forge->addKey('customer_uid');
        $this->forge->addKey('object_uid');
        $this->forge->addKey('appointment_uid');

        //$this->forge->addForeignKey('user_uid', 'user', 'user_uid');
        $this->forge->createTable('event');
    }

    public function down()
    {
        $this->forge->dropTable('event', true);

    }
}
