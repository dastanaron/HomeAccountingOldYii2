<?php

namespace backend\components\API\funds;

use backend\models\Balance;
use common\models\User;

trait FundsTrait
{
    /**
     * @var Balance
     */
    public $balance;

    /**
     * @var array
     */
    public $params;

    /**
     * @var array
     */
    public $categories;

    /**
     * @var integer
     */
    public $selectedCategory;

    /**
     * @var array
     */
    public $period;

    /**
     * @var array|bool
     */
    public $error = false;

    /**
     * @return bool
     */
    protected function validate()
    {

        //Проверка существования категории
        if(isset($this->categories['categories'])) {

            $category = mb_strtolower($this->categories['categories']);

            $this->selectedCategory = array_search($category, $this->categories);

            $this->error['categories'] = !empty($this->selectedCategory) ? false : true;

        }

        //Распарсивание периода
        if(isset($this->categories['period'])) {

            $period = explode('|', $this->categories['period']);

            $date_start = new \DateTime($period[0]);
            $date_end = new \DateTime($period[1]);

            $this->period['start'] = $date_start->getTimestamp();
            $this->period['end'] = $date_end->getTimestamp();

            if(empty($this->period)) {
                $this->error['period'] = 'period format is invalid';
            }

        }


        return empty($this->error) ? true : false;

    }


}