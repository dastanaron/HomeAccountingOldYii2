<?php

use yii\db\Migration;
use dastanaron\yiimigrate\updater\TableData;

/**
 * Class m130524_201442_init
 */
class m130524_201442_init extends Migration
{
    public $tableName = 'user';

    /**
     * @return bool|void
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'vk_id' => $this->string(55)->null(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'access' => $this->integer()->defaultValue(2),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $tableData = new TableData($this->tableName);

        $sqldump = $tableData->Dump('read');

        if(!empty($sqldump)) {
            $this->execute($sqldump);
        }
    }

    /**
     * @return bool|void
     */
    public function down()
    {
        $tableData = new TableData($this->tableName);

        $tableData->Dump('create');

        $this->dropTable($this->tableName);
    }
}
