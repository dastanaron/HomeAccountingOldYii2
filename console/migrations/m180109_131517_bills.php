<?php

use yii\db\Migration;

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
            'created_at' => $this->dateTime()->defaultValue(new DateTime()),
            'updated_at' => $this->dateTime()->null(),
        ], $tableOptions);

        $this->addForeignKey('fk_bills_1', $this->tableName, 'balance_id', 'current_balance', 'id');

        $this->AlterTable();

    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }

    public function AlterTable()
    {
        $sql = "ALTER TABLE `$this->tableName` CHANGE `updated_at` `updated_at` DATETIME on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;";
        $this->execute($sql);
    }
}