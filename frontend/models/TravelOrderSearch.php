<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TravelOrder;

/**
 * TravelOrderSearch represents the model behind the search form of `app\models\TravelOrder`.
 */
class TravelOrderSearch extends TravelOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'date',
                'destination',
                'created_at',
                'purpose',
                'expected_outputs',
                'to_number',
                'fk_approved_by',
                'fk_recommending_approval',
                'fk_budget_officer'
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
        $query = TravelOrder::find();

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
        $query->joinWith('approvedBy as approved_by');
        $query->joinWith('recommendingApproval as recommending_approval');
        $query->joinWith('budgetOfficer as budget_officer');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'destination', $this->destination]);
        $query->andFilterWhere(['like', 'purpose', $this->purpose]);
        $query->andFilterWhere(['like', 'expected_outputs', $this->expected_outputs]);
        $query->andFilterWhere(['like', 'to_number', $this->to_number]);
        $query->andFilterWhere([
            'or', ['like', 'approved_by.f_name', $this->fk_approved_by],
            ['like', 'approved_by.m_name', $this->fk_approved_by],
            ['like', 'approved_by.l_name', $this->fk_approved_by]
        ]);
        $query->andFilterWhere([
            'or', ['like', 'recommending_approval.f_name', $this->fk_recommending_approval],
            ['like', 'recommending_approval.m_name', $this->fk_recommending_approval],
            ['like', 'recommending_approval.l_name', $this->fk_recommending_approval]
        ]);
        $query->andFilterWhere([
            'or', ['like', 'budget_officer.f_name', $this->fk_budget_officer],
            ['like', 'budget_officer.m_name', $this->fk_budget_officer],
            ['like', 'budget_officer.l_name', $this->fk_budget_officer]
        ]);






        return $dataProvider;
    }
}
