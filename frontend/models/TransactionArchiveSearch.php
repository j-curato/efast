<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransactionArchive;

/**
 * TransactionArchiveSearch represents the model behind the search form of `app\models\TransactionArchive`.
 */
class TransactionArchiveSearch extends TransactionArchive
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'account_name',
                'tracking_number',
                'ors_number',
                'total_obligation',
                'dv_number',
                'check_or_ada_no',
                'ada_number',

            ], 'safe'],
            [[
                'gross_amount',
                'amount_disbursed',
                'vat_nonvat',
                'ewt_goods_services',
                'compensation',
                'other_trust_liabilities',
            ], 'number'],
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
        $query = TransactionArchive::find();

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
        // $query->andFilterWhere([
        //     'id' => $this->id,
        //     'responsibility_center_id' => $this->responsibility_center_id,
        //     'gross_amount' => $this->gross_amount,
        // ]);

        $query->andFilterWhere(['like', 'account_name', $this->account_name])
            ->andFilterWhere(['like', 'tracking_number', $this->tracking_number])
            ->andFilterWhere(['like', 'ors_number', $this->ors_number])
            ->andFilterWhere(['like', 'total_obligation', $this->total_obligation])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'check_or_ada_no', $this->check_or_ada_no])
            ->andFilterWhere(['like', 'gross_amount', $this->gross_amount])
            ->andFilterWhere(['like', 'amount_disbursed', $this->amount_disbursed])
            ->andFilterWhere(['like', 'vat_nonvat', $this->vat_nonvat])
            ->andFilterWhere(['like', 'ewt_goods_services', $this->ewt_goods_services])
            ->andFilterWhere(['like', 'compensation', $this->compensation])
            ->andFilterWhere(['like', 'other_trust_liabilities', $this->other_trust_liabilities])
            ->andFilterWhere(['like', 'ada_number', $this->ada_number]);

        return $dataProvider;
    }
}
