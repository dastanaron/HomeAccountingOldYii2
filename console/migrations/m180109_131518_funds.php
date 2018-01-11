<?php

use yii\db\Migration;
use dastanaron\yiimigrate\updater\TableData;

/**
 * Class m180109_131518_funds
 */
class m180109_131518_funds extends Migration
{
    public $tableName = 'funds';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(0),
            'bill_id' => $this->integer()->null(),
            'arrival_or_expense' => $this->integer(1)->notNull(),
            'category' => $this->integer(4)->null(),
            'sum' => $this->string(10)->notNull(),
            'cause' => $this->string(200)->null(),
            'date' => $this->string(50)->null(),
            'cr_time' => $this->dateTime()->notNull(),
            'up_time' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->AlterTable();

        $tableData = new TableData($this->tableName);

        $sqldump = $tableData->Dump('read');

        if(!empty($sqldump)) {
            $this->execute($sqldump);
        }

        $this->addForeignKey('fk_bills_2', $this->tableName, 'bill_id', 'bills', 'id');

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


    /*public function createTable($table, $columns, $options = null)
    {
       $this->dropTable($this->tableName);
       parent::createTable($table, $columns, $options = null);
    }*/

}
