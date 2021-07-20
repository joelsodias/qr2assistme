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

            'chat_user_uid'         => [
                'type'              => 'CHAR',
                'constraint'        => 36,
                'null'              => true,
            ],

            'schedule_status'         => [
                'type'              => 'ENUM("draft","requested","scheduled","rescheduled","canceled","closed")',
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

            'schedule_started_at'       => [
                'type'           => 'TIMESTAMP',
                'null'              => true,
            ],

            'schedule_ended_at'       => [
                'type'           => 'TIMESTAMP',
                'null'              => true,
            ],


            
            'schedule_object_name' => [
                'type' => 'VARCHAR',
                'constraint'        => 50,
                'null' => true,
            ],

            'schedule_service_name' => [
                'type' => 'VARCHAR',
                'constraint'        => 50,
                'null' => false,
            ],

            'schedule_contact_name'       => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null' => false,
            ],

            'schedule_contact_phone'       => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],

            'schedule_city'       => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null' => false,
            ],

            'schedule_address'       => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null' => false,
            ],

            'schedule_description' => [
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
