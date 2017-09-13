<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $head_event
 * @property string $message_event
 * @property integer $completed
 * @property string $date_notification
 * @property string $timestamp
 */
class Events extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'completed'], 'integer'],
            [['message_event'], 'string'],
            [['date_notification', 'timestamp'], 'safe'],
            [['head_event'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Связка с пользователем',
            'head_event' => 'Заголовок события',
            'message_event' => 'Сообщение напоминания',
            'completed' => 'Выполнено',
            'date_notification' => 'Время напоминания',
            'timestamp' => 'Timestamp',
        ];
    }
}
