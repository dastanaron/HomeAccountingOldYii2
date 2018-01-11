<?php

use yii\db\Migration;
use dastanaron\yiimigrate\updater\TableData;

class m170913_191929_events extends Migration
{

    public $tableName = 'events';

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
            'user_id' => $this->integer()->defaultValue(0),
            'head_event' => $this->string(55)->null(),
            'message_event' => $this->text()->null(),
            'completed' => $this->boolean()->defaultValue(false),
            'date_notification' => $this->timestamp(),
            'timestamp' => $this->timestamp()->defaultValue(null),
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
