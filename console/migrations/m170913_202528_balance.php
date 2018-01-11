<?php

use yii\db\Migration;
use dastanaron\yiimigrate\updater\TableData;

class m170913_202528_balance extends Migration
{

    public $tableName = 'balance';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(0),
            'total_sum' => $this->integer()->defaultValue(0),
            'up_time' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->AlterTable();

        $tableData = new TableData($this->tableName);

        $sqldump = $tableData->Dump('read');

        if(!empty($sqldump)) {
            $this->execute($sqldump);
        }

    }

    public function down()
    {
        $tableData = new TableData($this->tableName);

        $tableData->Dump('create');

        $this->dropTable($this->tableName);
    }

    public function AlterTable()
    {
        $sql = "ALTER TABLE `$this->tableName` CHANGE `up_time` `up_time` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;";
        $this->execute($sql);
    }
}
