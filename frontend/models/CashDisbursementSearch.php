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
            [['id', 'book_id', 'dv_aucs_id','dv_aucs_entries_id'], 'integer'],
            [['reporting_period', 'mode_of_payment', 'check_or_ada_no', 'is_cancelled', 'issuance_date'], 'safe'],
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'book_id' => $this->book_id,
            'dv_aucs_entries_id' => $this->dv_aucs_entries_id,
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'mode_of_payment', $this->mode_of_payment])
            ->andFilterWhere(['like', 'check_or_ada_no', $this->check_or_ada_no])
            ->andFilterWhere(['like', 'is_cancelled', $this->is_cancelled])
            ->andFilterWhere(['like', 'issuance_date', $this->issuance_date]);

        return $dataProvider;
    }
}
