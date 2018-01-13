<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Bills;
use yii\db\Query;

/**
 * BillsSearch represents the model behind the search form of `backend\models\Bills`.
 */
class BillsSearch extends Bills
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'balance_id', 'sum'], 'integer'],
            [['comment','name', 'deadline', 'created_at', 'updated_at'], 'safe'],
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
        $query = (new Query())
            ->select(['bills.*'])
            ->from('bills')
            ->leftJoin('balance', 'bills.balance_id=balance.id')
            ->where(['balance.user_id' => Yii::$app->user->identity->getId()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'balance_id' => $this->balance_id,
            'sum' => $this->sum,
            'deadline' => $this->deadline,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
