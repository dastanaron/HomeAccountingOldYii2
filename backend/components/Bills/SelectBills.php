<?php
/**
 * Created by PhpStorm.
 * User: dastanaron
 * Date: 11.01.18
 * Time: 11:22
 */

namespace backend\components\Bills;

use frontend\models\PasswordResetRequestForm;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use backend\models\Bills;

/**
 * The static class to getter bills
 * Class SelectBills
 * @package backend\components\Bills
 */
class SelectBills
{


    public static function getBillsByUserArray()
    {
        /** @var $models  Bills*/
        $models = self::getBillsByUser()->all();

        $array = array();

        foreach($models as $element) {
            $array[$element['id']] = $element['name'];
        }

        return $array;

    }

    public static function getBillsByUser()
    {
        $query = self::getBills()->where(['balance.user_id' => Yii::$app->user->identity->getId()]);
        return $query;
    }

    private static function getBills()
    {
        $query = (new Query())
            ->select([
                'bills.*',
            ])
            ->from('balance')
            ->leftJoin('bills', 'balance.id = bills.balance_id');

        return $query;
    }

}