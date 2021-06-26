<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\DB;
use Exception;

class BaseProcedureMigration extends Migration
{

    protected $createFileName;
    protected $dropFileName;
    protected $objectName;
    protected $createSQL;
    protected $dropSQL;

    public function up()
    {
        if ((isset($this->createSQL) && isset($this->procedureName))) {
        } elseif (isset($this->createFileName)) {

            try {
                $path = realpath("./../app/Database/Procedures/" . $this->createFileName);
                $sql = file_get_contents($path);

                if ($sql) {
                    $this->db->query("DROP PROCEDURE IF EXISTS $this->objectName");
                    $this->db->query($sql);
                }

            } catch (Exception $e) {
                throw new Exception("Error trying to read/execute creation SQL file ($this->createFileName): $e->message ");
            }

        } else throw new Exception("Error: Not enought configuration to exectute actions (Both procedureName or createFile not set).");
    }

    public function down()
    {
        if ($this->dropFileName) {
            try {
                $path = realpath("./../Procedures/" . $this->filename);
                $sql = file_get_contents($path);

                if ($sql) {
                    $this->db->query($sql);
                }
            } catch (Exception $e) {
                throw new Exception("Error trying to execute drop file ($this->dropFileName): $e->message ");
            }
        } elseif (isset($this->dropSQL)) {
            try {
                $this->db->query($this->dropSQL);
            } catch (Exception $e) {
                throw new Exception("Error trying to drop object using custom SQL: $e->message");
            }
        } elseif (isset($this->objectName)) {
            try {
                $this->db->query("DROP PROCEDURE IF EXISTS $this->objectName");
            } catch (Exception $e) {
                throw new Exception("Error trying to drop object ($this->objectName): $e->message");
            }
        } else throw new Exception("Error: Not enought configuration to exectute actions (Both procedureName and dropFile not set).");
    }
}
