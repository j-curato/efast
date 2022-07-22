<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrSummary;

/**
 * PrSummarySearch represents the model behind the search form of `app\models\PrSummary`.
 */
class PrSummarySearch extends PrSummary
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purpose'], 'string'],
            [['po_id', 'aoq_id', 'rfq_id', 'pr_id'], 'integer'],
            [['po_number', 'aoq_number', 'rfq_number', 'pr_number', 'payee'], 'string', 'max' => 255],
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
        $query = PrSummary::find();

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
        // $query->andFilterWhere([]);

        $query->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'aoq_number', $this->aoq_number])
            ->andFilterWhere(['like', 'rfq_number', $this->rfq_number])
            ->andFilterWhere(['like', 'pr_number', $this->pr_number])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'purpose', $this->purpose]);

        return $dataProvider;
    }
}
