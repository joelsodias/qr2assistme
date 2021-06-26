<?php

namespace App\Database\Migrations;

use App\Database\Migrations\BaseTableMigration;

class TestTable extends BaseTableMigration
{
	public function up()
	{
		parent::up();
		
		$this->forge->addField([
			"id" => [
				"type" => "INT",
				"auto_increment" => true,
				"unsigned" => true,
				"constraint" => 5
			],
			"name" => [
				"type" => "VARCHAR",
				"constraint" => 250,
				"null" => false
			],
			"description" => [
				"type" => "TEXT",
				"null" => true
			],
			"cost" => [
				"type" => "INT",
				"constraint" => 11,
				"null" => false
			],
			"product_image_url" => [
				"type" => "VARCHAR(1000) CHARACTER SET utf8",
				"null" => true
			]
		]);

		$this->forge->addPrimaryKey("id");
		$this->forge->createTable("tests");
	}

	public function down()
	{
		$this->forge->dropTable("tests", true);
	}
}
