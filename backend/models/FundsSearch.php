<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Funds;

/**
 * FundsSearch represents the model behind the search form about `backend\models\Funds`.
 */
class FundsSearch extends Funds
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'arrival_or_expense', 'category'], 'integer'],
            [['summ', 'cause', 'date', 'cr_time', 'up_time'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Funds::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'arrival_or_expense' => $this->arrival_or_expense,
            'category' => $this->category,
            'summ' => $this->summ,
            'date' => $this->date,
            'cr_time' => $this->cr_time,
            'up_time' => $this->up_time,
        ]);

        $query->andFilterWhere(['like', 'cause', $this->cause]);

        return $dataProvider;
    }
}
