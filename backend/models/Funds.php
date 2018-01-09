<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "funds".
 *
 * @property integer $id
 * @property integer $user_id
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
            [['arrival_or_expense', 'sum', 'cr_time', 'user_id'], 'required'],
            [['arrival_or_expense', 'category', 'user_id'], 'integer'],
            [['cr_time', 'up_time'], 'safe'],
            [['cause'], 'string', 'max' => 200],
            [['sum'], 'integer'],
            //[['date'], 'integer', 'max' => 50],
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
            'sum' => 'Сумма',
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
            0 => '',
            1 => 'Продукты питания',
            2 => 'Питание в столовых и кафе',
            3 => 'Покупки для дома',
            5 => 'Развлечения',
            6 => 'Здоровье',
            7 => 'Занял',
            8 => 'Другие',
            9 => 'Транспорт',
            10 => 'Одежда и обувь',
            11 => 'Услуги быта и связи',
        ];

    }

    /**
     * @return array
     */
    public static function ArrivalOrExpens()
    {

        return [
            0 => '',
            1 => 'Приход',
            2 => 'Расход',
        ];

    }

    public static function DateToTimestamp($date) {

        $datetime = new \DateTime($date);

        return $datetime->getTimestamp();

    }

    public static function TimestampToDate($timestamp, $hour = false) {

        if($hour) {
            $format = 'd.m.Y H:i:s';
        }
        else {
            $format = 'd.m.Y';
        }

        return date($format, $timestamp);

    }
}
