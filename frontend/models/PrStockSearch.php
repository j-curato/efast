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
            [['id',  'chart_of_account_id'], 'integer'],
            [['stock_title', 'created_at', 'unit_of_measure_id', 'bac_code'], 'safe'],
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
        $query->joinWith('unitOfMeasure');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
            'chart_of_account_id' => $this->chart_of_account_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'stock_title', $this->stock_title])
            ->andFilterWhere(['like', 'unit_of_measure.unit_of_measure', $this->unit_of_measure_id])
            ->andFilterWhere(['like', 'bac_code', $this->bac_code]);

        return $dataProvider;
    }
}
