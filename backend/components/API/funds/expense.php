<?php

namespace backend\components\API\funds;

use backend\models\Balance;
use backend\models\Funds;


/**
 * Class expense
 * @package backend\components\API\funds
 */
class expense extends AbstractFunds
{

    protected function query()
    {
        $this->query = Funds::find()->where(['arrival_or_expense' => 2]);
    }


}