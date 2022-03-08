<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrPurchaseOrder;

/**
 * PrPurchaseOrderSearch represents the model behind the search form of `app\models\PrPurchaseOrder`.
 */
class PrPurchaseOrderSearch extends PrPurchaseOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_contract_type_id', 'fk_mode_of_procurement_id', 'fk_pr_aoq_id', 'fk_auth_official', 'fk_accounting_unit'], 'integer'],
            [['po_number', 'place_of_delivery', 'delivery_date', 'payment_term','delivery_term'], 'safe'],
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
        $query = PrPurchaseOrder::find();

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
            'fk_contract_type_id' => $this->fk_contract_type_id,
            'fk_mode_of_procurement_id' => $this->fk_mode_of_procurement_id,
            'fk_pr_aoq_id' => $this->fk_pr_aoq_id,
            'delivery_date' => $this->delivery_date,
            'fk_auth_official' => $this->fk_auth_official,
            'fk_accounting_unit' => $this->fk_accounting_unit,
        ]);

        $query->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'place_of_delivery', $this->place_of_delivery])
            ->andFilterWhere(['like', 'delivery_term', $this->delivery_term])
            ->andFilterWhere(['like', 'payment_term', $this->payment_term]);

        return $dataProvider;
    }
}
