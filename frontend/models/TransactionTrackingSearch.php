<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransactionTracking;

/**
 * TransactionTrackingSearch represents the model behind the search form of `app\models\TransactionTracking`.
 */
class TransactionTrackingSearch extends TransactionTracking
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'tracking_number',
                'division',
                'gross_amount',
                'transaction_date',
                'payee',
                'particular',
                'ors_number',
                'ors_date',
                'created_at',
                'dv_number',
                'recieved_at',
                'in_timestamp',
                'out_timestamp',
                'check_or_ada_no',
                'issuance_date',
                'cash_is_cancelled',
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
        $query = TransactionTracking::find();

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
        ]);

        $query->andFilterWhere(['like', 'tracking_number', $this->tracking_number])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'gross_amount', $this->gross_amount])
            ->andFilterWhere(['like', 'transaction_date', $this->transaction_date])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'ors_number', $this->ors_number])
            ->andFilterWhere(['like', 'ors_date', $this->ors_date])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'recieved_at', $this->recieved_at])
            ->andFilterWhere(['like', 'in_timestamp', $this->in_timestamp])
            ->andFilterWhere(['like', 'out_timestamp', $this->out_timestamp])
            ->andFilterWhere(['like', 'check_or_ada_no', $this->check_or_ada_no])
            ->andFilterWhere(['like', 'issuance_date', $this->issuance_date])
            ->andFilterWhere(['like', 'cash_is_cancelled', $this->cash_is_cancelled]);

        return $dataProvider;
    }
}
