<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CashAdjustment;

/**
 * CashAdjustmentSearch represents the model behind the search form of `app\models\CashAdjustment`.
 */
class CashAdjustmentSearch extends CashAdjustment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'book_id'], 'integer'],
            [['particular', 'date'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = CashAdjustment::find();

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
            'book_id' => $this->book_id,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'date', $this->date]);

        return $dataProvider;
    }
}
