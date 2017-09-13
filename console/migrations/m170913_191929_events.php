<?php

use yii\db\Migration;

class m170913_191929_events extends Migration
{
    /*public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m170913_191929_events cannot be reverted.\n";

        return false;
    }*/

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('events', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(0),
            'head_event' => $this->string(55)->null(),
            'message_event' => $this->text()->null(),
            'completed' => $this->boolean()->defaultValue(false),
            'date_notification' => $this->timestamp(),
            'timestamp' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('events');
    }
}
