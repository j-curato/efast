<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProcurementSummary;

/**
 * ProcurementSummarySearch represents the model behind the search form of `app\models\ProcurementSummary`.
 */
class ProcurementSummarySearch extends ProcurementSummary
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'project_title',
                'prepared_by',
                'pr_created_at',
                'pr_number',
                'pr_date',
                'pr_requested_by',
                'pr_approved_by',
                'purpose',
                'stock_title',
                'specification',
                'unit_of_measure',
                'quantity',
                'unit_cost',
                'rfq_created_at',
                'rfq_number',
                'rfq_date',
                'rfq_deadline',
                'canvasser',
                'aoq_created_at',
                'aoq_number',
                'aoq_date',
                'supplier_bid_amount',
                'lowest',
                'remark',
                'payee',
                'po_created_at',
                'po_number',
                'contract_type',
                'mode_of_procurement',
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
        $query = ProcurementSummary::find();



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
            'supplier_bid_amount' => $this->supplier_bid_amount,

        ]);

        $query
            ->andFilterWhere(['like', 'project_title', $this->project_title])
            ->andFilterWhere(['like', 'prepared_by', $this->prepared_by])
            ->andFilterWhere(['like', 'pr_created_at', $this->pr_created_at])
            ->andFilterWhere(['like', 'pr_number', $this->pr_number])
            ->andFilterWhere(['like', 'pr_date', $this->pr_date])
            ->andFilterWhere(['like', 'pr_requested_by', $this->pr_requested_by])
            ->andFilterWhere(['like', 'pr_approved_by', $this->pr_approved_by])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'stock_title', $this->stock_title])
            ->andFilterWhere(['like', 'specification', $this->specification])
            ->andFilterWhere(['like', 'unit_of_measure', $this->unit_of_measure])
            ->andFilterWhere(['like', 'quantity', $this->quantity])
            ->andFilterWhere(['like', 'unit_cost', $this->unit_cost])
            ->andFilterWhere(['like', 'rfq_created_at', $this->rfq_created_at])
            ->andFilterWhere(['like', 'rfq_number', $this->rfq_number])
            ->andFilterWhere(['like', 'rfq_date', $this->rfq_date])
            ->andFilterWhere(['like', 'rfq_deadline', $this->rfq_deadline])
            ->andFilterWhere(['like', 'canvasser', $this->canvasser])
            ->andFilterWhere(['like', 'aoq_created_at', $this->aoq_created_at])
            ->andFilterWhere(['like', 'aoq_number', $this->aoq_number])
            ->andFilterWhere(['like', 'aoq_date', $this->aoq_date])
            ->andFilterWhere(['like', 'supplier_bid_amount', $this->supplier_bid_amount])
            ->andFilterWhere(['like', 'lowest', $this->lowest])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'po_created_at', $this->po_created_at])
            ->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'contract_type', $this->contract_type])
            ->andFilterWhere(['like', 'mode_of_procurement', $this->mode_of_procurement]);

        return $dataProvider;
    }
}
