<?php

use yii\db\Migration;
use dastanaron\yiimigrate\updater\TableData;

/**
 * Class m180109_131517_bills
 */
class m180109_131517_bills extends Migration
{
    public $tableName = 'bills';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'balance_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'sum' => $this->integer()->notNull()->defaultValue(0),
            'deadline' => $this->dateTime()->null(),
            'comment' => $this->text()->null(),
            'created_at' => $this->dateTime()->null(),
            'updated_at' => $this->dateTime()->null(),
        ], $tableOptions);

        $this->addForeignKey('fk_bills_1', $this->tableName, 'balance_id', 'balance', 'id');

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
        $sql = "ALTER TABLE `$this->tableName` CHANGE `updated_at` `updated_at` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;";
        $this->execute($sql);
    }
}