<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class ScheduleTable extends BaseTableMigration
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

            'schedule_uid'          => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                "null"              => false
            ],

            'worker_uid'         => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                'null'              => false,
            ],

            'ticket_uid'         => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                'null'              => true,
            ],

            'schedule_status'         => [
                'type'              => 'ENUM("daft","scheduled","rescheduled","canceled","closed")',
                'null'              => false,
            ],

            'schedule_date'       => [
                'type'           => 'TIMESTAMP',
                'null'              => false,
            ],

            'schedule_by_uid'         => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                'null'              => true,
            ],

            'started_at'       => [
                'type'           => 'TIMESTAMP',
                'null'              => false,
            ],

            'ended_at'       => [
                'type'           => 'TIMESTAMP',
                'null'              => false,
            ],

            'service_name' => [
                'type' => 'VARCHAR',
                'constraint'        => 50,
                'null' => false,
            ],

            'contact_name'       => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null' => false,
            ],

            'contact_phone'       => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],

            'city'       => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null' => false,
            ],

            'address'       => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null' => false,
            ],

            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],


        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey("schedule_uid", false, true);
        $this->forge->createTable('schedule');
    }

    public function down()
    {
        $this->forge->dropTable('schedule', true);
    }
}
