<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrStock;

/**
 * PrStockSearch represents the model behind the search form of `app\models\PrStock`.
 */
class PrStockSearch extends PrStock
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bac_code', 'unit_of_measure_id', 'chart_of_account_id'], 'integer'],
            [['stock', 'created_at'], 'safe'],
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
        $query = PrStock::find();

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
            'bac_code' => $this->bac_code,
            'unit_of_measure_id' => $this->unit_of_measure_id,
            'amount' => $this->amount,
            'chart_of_account_id' => $this->chart_of_account_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'stock', $this->stock]);

        return $dataProvider;
    }
}
