<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CashDisbursement;

/**
 * CashDisbursementSearch represents the model behind the search form of `app\models\CashDisbursement`.
 */
class CashDisbursementSearch extends CashDisbursement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reporting_period', 'dv_aucs_id', 'mode_of_payment', 'check_or_ada_no', 'is_cancelled', 'issuance_date', 'ada_number', 'book_id'], 'safe'],
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
        $query = CashDisbursement::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith('dvAucs');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('dvAucs');
        $query->joinWith('book');

        // grid filtering conditions
        $query->andFilterWhere([
            'cash_disbursement.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'cash_disbursement.reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'mode_of_payment', $this->mode_of_payment])
            ->andFilterWhere(['like', 'cash_disbursement.check_or_ada_no', $this->check_or_ada_no])
            ->andFilterWhere(['like', 'ada_number', $this->ada_number])
            ->andFilterWhere(['like', 'cash_disbursement.is_cancelled', $this->is_cancelled])
            ->andFilterWhere(['like', 'dv_aucs.dv_number', $this->dv_aucs_id])
            ->andFilterWhere(['like', 'cash_disbursement.issuance_date', $this->issuance_date])
            ->andFilterWhere(['like', 'books.name', $this->book_id]);

        return $dataProvider;
    }
}
