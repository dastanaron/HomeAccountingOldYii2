<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "balance".
 *
 * @property int $id
 * @property int $user_id
 * @property int $total_sum
 * @property string $up_time
 *
 * @property Bills[] $bills
 */
class Balance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'total_sum'], 'integer'],
            [['up_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'total_sum' => 'Общая сумма',
            'up_time' => 'Последнее изменение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBills()
    {
        return $this->hasMany(Bills::className(), ['balance_id' => 'id']);
    }
}
