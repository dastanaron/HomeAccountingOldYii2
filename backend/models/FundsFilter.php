<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Funds;

/**
 * FundsSearch represents the model behind the search form about `backend\models\Funds`.
 */
class FundsFilter extends Funds
{
    protected $date_start;
    protected $date_end;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'arrival_or_expense', 'category'], 'integer'],
            [['summ', 'cause', 'date', 'cr_time', 'up_time', 'date_start', 'date_end'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return array|\yii\db\ActiveRecord[]
     */
    public function search($params)
    {
        $query = Funds::find();


        //$this->load($params);
        $this->LoadParams ($params);

        //Защита от большого числа записей при заходе на страницу, нужно будет написать лимит, когда пойму сколько нужно
        if(empty($this->arrival_or_expense)) {
            $this->arrival_or_expense = 1;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' =>  Yii::$app->user->getId(),
            'arrival_or_expense' => $this->arrival_or_expense,
            'category' => $this->category,
            'summ' => $this->sum,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(
            ['>=', 'date', $this->date_start]
        )->andFilterWhere(
            ['<=', 'date', $this->date_end]
        )->andFilterWhere(['like', 'cause', $this->cause])
            ->orderBy(['date' => SORT_ASC]);

        return $query->all();
    }

    private function LoadParams ($params) {

        foreach ($params as $key => $value) {
            if (!empty($value) && $key != '_csrf-backend') {
                $this->$key = $value;
            }
            if (!empty($value) && ($key == 'date_start' || $key == 'date_end')) {
                $this->$key = parent::DateToTimestamp($value);
            }
        }

    }
}
