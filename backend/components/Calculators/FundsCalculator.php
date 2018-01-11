<?php
namespace backend\components\Calculators;


use backend\models\Bills;
use Yii;
use backend\models\Balance;
use yii\helpers\VarDumper;

/**
 * Class FundsCalculator
 * @package backend\components\Calculators
 */
class FundsCalculator
{

    public static function CalculateBalance()
    {
        /** @var Bills $bill */

        $balance = Balance::findOne(['user_id' =>  Yii::$app->user->getId()]);

        $bills = Bills::find(['balance_id' => $balance->id])->all();

        $total_sum = 0;

        foreach($bills as $bill) {
            $total_sum += $bill->sum;
        }

        $balance->total_sum = $total_sum;

        $balance->save();

        return $total_sum;

    }

    /**
     * @param Bills $bill
     * @param $sum
     * @param $dynamic
     * @param bool $update
     * @param null $beforeSum
     * @return bool
     * @throws \Exception
     */
    public static function calculateBill(Bills $bill, $sum, $dynamic, $update = false, $beforeSum = null)
    {
        //получаем снова сумму до вычета или прихода
        if($update === true) {

            $oldSum = 0;

            if ($dynamic == '1') {
                $oldSum = $bill->sum - $beforeSum;
            }
            elseif ($dynamic == '2') {
                $oldSum = $bill->sum + $beforeSum;
            }

            $bill->sum = $oldSum;
        }

        $calcSum = 0;

        //Считаем сумму всех вычетов
        if ($dynamic == '1') {
            $calcSum = $bill->sum + $sum;
        }
        elseif ($dynamic == '2') {
            $calcSum = $bill->sum - $sum;
        }
        else {
            throw new \Exception('not isset dynamic');
        }


        $bill->sum = $calcSum;

        return $bill->save();

    }

}