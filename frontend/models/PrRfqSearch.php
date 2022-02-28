<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrRfq;

/**
 * PrRfqSearch represents the model behind the search form of `app\models\PrRfq`.
 */
class PrRfqSearch extends PrRfq
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pr_purchase_request_id', 'bac_composition_id'], 'integer'],
            [['rfq_number', '_date', 'employee_id', 'created_at'], 'safe'],
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
        $query = PrRfq::find();

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
            'pr_purchase_request_id' => $this->pr_purchase_request_id,
            '_date' => $this->_date,
            'bac_composition_id' => $this->bac_composition_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'rfq_number', $this->rfq_number])
            ->andFilterWhere(['like', 'employee_id', $this->employee_id]);

        return $dataProvider;
    }
}
