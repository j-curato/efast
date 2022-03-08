<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdvancesSearch represents the model behind the search form of `app\models\Advances`.
 */
class DetailedDvAucsSearch extends DetailedDvAucs
{
    /**
     * {@inheritdoc}
     */
    public $year;
    public function rules()
    {
        return [
            [[], 'integer'],
            [[
                'dv_number', 'obligation_number', 'particular',
                'transaction_tracking_number', 'reporting_period',
                'payee', 'mfo_name', 'mfo_code',
                'allotment_number', 'check_or_ada_no', 'ada_number', 'year'
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
        $query = DetailedDvAucs::find();

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
        $query->andFilterWhere([]);

        $query->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'obligation_number', $this->obligation_number])
            ->andFilterWhere(['like', 'transaction_tracking_number', $this->transaction_tracking_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'mfo_code', $this->mfo_code])
            ->andFilterWhere(['like', 'allotment_number', $this->allotment_number])
            ->andFilterWhere(['like', 'particular', $this->particular]);

        return $dataProvider;
    }
}
