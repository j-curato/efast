<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UnpaidObligation;

/**
 * UnpaidObligationSearch represents the model behind the search form of `app\models\UnpaidObligation`.
 */
class UnpaidObligationSearch extends UnpaidObligation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'total_amount',
                'total_amount_disbursed',
                'unpaid_obligation',
                'amount_disbursed',
                'vat_nonvat',
                'ewt_goods_services',
                'compensation',
                'other_trust_liabilities',
                'total_withheld',
            ], 'number'],
            [[
                'reporting_period',
                'serial_number',
                'dv_number',
                'check_number',
                'is_cancelled',

            ], 'safe'],
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
        $query = UnpaidObligation::find();

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
           
        ]);


        $query->andFilterWhere(['like', 'total_amount', $this->total_amount])
        ->andFilterWhere(['like', 'total_amount_disbursed', $this->total_amount_disbursed])
        ->andFilterWhere(['like', 'unpaid_obligation', $this->unpaid_obligation])
        ->andFilterWhere(['like', 'amount_disbursed', $this->amount_disbursed])
        ->andFilterWhere(['like', 'vat_nonvat', $this->vat_nonvat])
        ->andFilterWhere(['like', 'ewt_goods_services', $this->ewt_goods_services])
        ->andFilterWhere(['like', 'compensation', $this->compensation])
        ->andFilterWhere(['like', 'other_trust_liabilities', $this->other_trust_liabilities])
        ->andFilterWhere(['like', 'total_withheld', $this->total_withheld])
        ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
        ->andFilterWhere(['like', 'serial_number', $this->serial_number])
        ->andFilterWhere(['like', 'dv_number', $this->dv_number])
        ->andFilterWhere(['like', 'check_number', $this->check_number])
        ->andFilterWhere(['like', 'is_cancelled', $this->is_cancelled])
        ;

        return $dataProvider;
    }
}
