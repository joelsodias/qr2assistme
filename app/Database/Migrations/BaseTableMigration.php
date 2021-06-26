<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BaseTableMigration extends Migration
{
	public function up()
	{
        $this->forge->addField([

            'created_at'       => [
                'type'       => 'TIMESTAMP',
                'default'       => 'CURRENT_TIMESTAMP',
            ],
            
            'updated_at'       => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'       => 'CURRENT_TIMESTAMP',
                'onUpdate' => 'CURRENT_TIMESTAMP',
            ],

            'deleted_at'       => [
                'type'       => 'TIMESTAMP',
                'null'       => true,

            ],

        ]);
	}

	public function down()
	{
		//
	}
}
