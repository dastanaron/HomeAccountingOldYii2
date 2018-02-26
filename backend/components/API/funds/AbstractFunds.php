<?php

namespace backend\components\API\funds;

use backend\models\Balance;
use backend\models\Funds;
use yii\db\Query;

/**
 * Class AbstractFunds
 * @package backend\components\API\funds
 */
abstract class AbstractFunds
{
    /**
     * @var Query;
     */
    public $query;

    use FundsTrait;

    public function __construct(Balance $balance, $params)
    {
        $this->balance = $balance;
        $this->params = $params;

        $this->categories = Funds::CategoriesList();

        $this->validate();

        $this->query();

    }

    abstract protected function query();


    public function category()
    {
        $this->query = $this->query->andWhere(['category' => $this->selectedCategory]);
    }

    public function period()
    {
        $this->query = $this->query->andWhere(['>=','date', $this->period[0]])->andWhere(['<=','date', $this->period[1]]);
    }

    public function request()
    {
        $this->query = $this->query->andWhere(['']);
    }

}