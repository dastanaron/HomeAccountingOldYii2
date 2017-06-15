<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "funds".
 *
 * @property integer $id
 * @property integer $arrival_or_expense
 * @property integer $category
 * @property string $cause
 * @property string $date
 * @property string $cr_time
 * @property string $up_time
 */
class Funds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'funds';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['arrival_or_expense', 'summ', 'cr_time'], 'required'],
            [['arrival_or_expense', 'category'], 'integer'],
            [['cr_time', 'up_time'], 'safe'],
            [['cause'], 'string', 'max' => 200],
            [['summ'], 'string', 'max' => 10],
            [['date'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'arrival_or_expense' => 'Приход или расход',
            'category' => 'Категория',
            'summ' => 'Сумма',
            'cause' => 'Причина',
            'date' => 'Дата',
            'cr_time' => 'Дата создания записи',
            'up_time' => 'Последнее обновление записи',
        ];
    }

    /**
     * @return array
     */
    public static function СategoriesList()
    {

        return [
            0 => 'Приход',
            1 => 'Продукты питания',
            2 => 'Питание в столовых и кафе',
            3 => 'Покупки для дома',
            5 => 'Развлечения',
            6 => 'Красота и здоровье',
            7 => 'Занял',
            8 => 'Другие',
        ];

    }

    /**
     * @return array
     */
    public static function ArrivalOrExpens()
    {

        return [
            1 => 'Приход',
            2 => 'Расход',
        ];

    }
}
