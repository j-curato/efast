<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DvAucs;

/**
 * DvAucsSearch represents the model behind the search form of `app\models\DvAucs`.
 */
class DvAucsSearch extends DvAucs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'dv_number', 'reporting_period',
                'particular',
                'transaction_begin_time',
                'return_timestamp',
                'accept_timestamp',
                'out_timestamp',
                'created_at',
                'payee_id',
                'mrd_classification_id',
                'nature_of_transaction_id',
                'recieved_at'
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
        $query = DvAucs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith('payee');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('mrdClassification');
        $query->joinWith('natureOfTransaction');

        // grid filtering conditions
        $query->andFilterWhere([
            'dv_aucs.id' => $this->id,
            'is_cancelled' => $this->is_cancelled,
            'recieved_at' => $this->recieved_at
        ]);

        $query->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'payee.account_name', $this->payee_id])
            ->andFilterWhere(['like', 'transaction_begin_time', $this->transaction_begin_time])
            ->andFilterWhere(['like', 'return_timestamp', $this->return_timestamp])
            ->andFilterWhere(['like', 'accept_timestamp', $this->accept_timestamp])
            ->andFilterWhere(['like', 'out_timestamp', $this->out_timestamp])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'mrd_classification.name', $this->mrd_classification_id])
            ->andFilterWhere(['like', 'nature_of_transaction.name', $this->nature_of_transaction_id]);

        return $dataProvider;
    }
}
