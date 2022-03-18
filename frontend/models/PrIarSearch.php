<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrIar;

/**
 * PrIarSearch represents the model behind the search form of `app\models\PrIar`.
 */
class PrIarSearch extends PrIar
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_pr_purchase_order_id', 'fk_inspection_officer', 'fk_property_custodian'], 'integer'],
            [['_date', 'reporting_period', 'invoice_number', 'invoice_date'], 'safe'],
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
        $query = PrIar::find();

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
            '_date' => $this->_date,
            'invoice_date' => $this->invoice_date,
            'fk_pr_purchase_order_id' => $this->fk_pr_purchase_order_id,
            'fk_inspection_officer' => $this->fk_inspection_officer,
            'fk_property_custodian' => $this->fk_property_custodian,
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'invoice_number', $this->invoice_number]);

        return $dataProvider;
    }
}
