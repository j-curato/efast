<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JevPreparation;

/**
 * JevPreparationSearch represents the model behind the search form of `app\models\JevPreparation`.
 */
class JevPreparationSearch extends JevPreparation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'responsibility_center_id', 'fund_cluster_code_id', 'book_id'], 'integer'],
            [['check_ada_number', 'reporting_period', 'date', 'jev_number', 'dv_number', 'lddap_number', 'explaination', 'ref_number', 'payee_id'], 'safe'],
            [[
                'responsibility_center_id',
                'fund_cluster_code_id',
                'reporting_period',
                'date',
                'jev_number',
                'lddap_number',
                'explaination',
                'ref_number',
                'cash_flow_id',
                'payee_id',
                'mrd_classification_id',
                'check_ada',
                'cadadr_serial_number',
                'check_ada_number',
                'book_id',
                'entry_type',
                'cash_disbursement_id',
                'check_ada_date',
                'dv_number',
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
        $query = JevPreparation::find()->orderBy('id DESC');

        // add conditions that should always apply here
        // $query->joinWith(['jevAccountingEntries']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('payee');
        // grid filtering conditions
        $query->andFilterWhere([
            'jev_preparation.id' => $this->id,
            'responsibility_center_id' => $this->responsibility_center_id,
            'fund_cluster_code_id' => $this->fund_cluster_code_id,
            'book_id' => $this->book_id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])

            ->andFilterWhere(['like', 'jev_number', $this->jev_number])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'ref_number', $this->ref_number])
            ->andFilterWhere(['like', 'lddap_number', $this->lddap_number])
            ->andFilterWhere(['like', 'explaination', $this->explaination])
            ->andFilterWhere(['like', 'check_ada_number', $this->check_ada_number])
            ->andFilterWhere(['like', 'payee.account_name', $this->payee_id])
            // ->andFilterWhere(['like', 'jev_accounting_entries.chart_of_account_id', 2])
        ;

        return $dataProvider;
    }
}
