<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrRfq;

/**
 * PrRfqSearch represents the model behind the search form of `app\models\PrRfq`.
 */
class PrRfqSearch extends PrRfq
{
    public $purpose;
    public $office_unit;
    public $project_title;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',  'bac_composition_id'], 'integer'],
            [[
                'rfq_number', '_date', 'employee_id', 'created_at', 'pr_purchase_request_id', 'purpose',
                'office_unit',
                'project_title'
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
        $query = PrRfq::find();

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
        $query->joinWith('purchaseRequest');
        $query->joinWith('purchaseRequest.projectProcurement');
        $query->joinWith('purchaseRequest.projectProcurement.office');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            '_date' => $this->_date,
            'bac_composition_id' => $this->bac_composition_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'rfq_number', $this->rfq_number])
            ->andFilterWhere(['like', 'employee_id', $this->employee_id])
            ->andFilterWhere(['like', 'pr_purchase_request.pr_number', $this->pr_purchase_request_id])
            ->andFilterWhere(['like', 'pr_purchase_request.purpose', $this->purpose])
            ->andFilterWhere(['like', 'pr_project_procurement.title', $this->project_title])
            ->andFilterWhere(['like', 'pr_office.unit', $this->office_unit]);

        return $dataProvider;
    }
}
