<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CancelledDisbursements;

/**
 * CancelledDisbursementsSearch represents the model behind the search form of `app\models\CancelledDisbursements`.
 */
class CancelledDisbursementsSearch extends CancelledDisbursements
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['id', 'parent_disbursement', 'is_cancelled'], 'integer'],
            [[
                'ada_number',
                'issuance_date',
                'check_or_ada_no',
                'mode_of_payment',
                'reporting_period',
                'dv_number',
                'book_name',
            ], 'safe'],
            [['dv_amount'], 'number'],
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
        $query = CancelledDisbursements::find();

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
            'parent_disbursement' => $this->parent_disbursement,
            'is_cancelled' => $this->is_cancelled,
        ]);

        $query->andFilterWhere(['like', 'ada_number', $this->ada_number])
            ->andFilterWhere(['like', 'issuance_date', $this->issuance_date])
            ->andFilterWhere(['like', 'check_or_ada_no', $this->check_or_ada_no])
            ->andFilterWhere(['like', 'mode_of_payment', $this->mode_of_payment])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'dv_amount', $this->dv_amount]);

        return $dataProvider;
    }
}
