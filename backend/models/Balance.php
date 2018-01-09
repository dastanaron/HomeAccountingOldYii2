<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "current_balance".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $summ_income
 * @property integer $summ_consumption
 * @property integer $total_summ
 * @property string $up_time
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
            [['summ_income', 'summ_consumption', 'total_summ', 'user_id'], 'integer'],
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
            'summ_income' => 'Сумма дохода',
            'summ_consumption' => 'Сумма расхода',
            'total_summ' => 'Сумма в наличии',
            'up_time' => 'Последнее обновление',
        ];
    }
}
