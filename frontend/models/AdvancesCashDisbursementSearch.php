<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdvancesCashDisbursement;

/**
 * AdvancesCashDisbursementSearch represents the model behind the search form of `app\models\AdvancesCashDisbursement`.
 */
class AdvancesCashDisbursementSearch extends AdvancesCashDisbursement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['id'], 'integer'],
            [['total_amount_disbursed'], 'number'],
            [[
                'mode_of_payment',
                'check_or_ada_no',
                'ada_number',
                'issuance_date',
                'dv_number',
                'payee',
                'particular',
                'book_name'
            ], 'safe'],
            [[
                'mode_of_payment',
                'check_or_ada_no',
                'ada_number',
                'issuance_date',
                'dv_number',
                'payee',
                'particular',
                'book_name'
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
        $query = AdvancesCashDisbursement::find();

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

        $query->andFilterWhere(['like', 'mode_of_payment', $this->mode_of_payment])
            ->andFilterWhere(['like', 'check_or_ada_no', $this->check_or_ada_no])
            ->andFilterWhere(['like', 'ada_number', $this->ada_number])
            ->andFilterWhere(['like', 'issuance_date', $this->issuance_date])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'total_amount_disbursed', $this->total_amount_disbursed]);

        return $dataProvider;
    }
}
