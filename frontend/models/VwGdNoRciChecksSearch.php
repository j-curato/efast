<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwGdNoRciChecks;

/**
 * VwGdNoRciChecksSearch represents the model behind the search form of `app\models\VwGdNoRciChecks`.
 */
class VwGdNoRciChecksSearch extends VwGdNoRciChecks
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['ttlDisbursed', 'ttlTax'], 'number'],
            [[
                'check_or_ada_no',
                'ada_number',
                'issuance_date',
                'reporting_period',
                'book_name',
                'mode_name'
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
        $query = VwGdNoRciChecks::find();

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

        $query->andFilterWhere(['like', 'check_or_ada_no', $this->check_or_ada_no])
            ->andFilterWhere(['like', 'ada_number', $this->ada_number])
            ->andFilterWhere(['like', 'issuance_date', $this->issuance_date])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'mode_name', $this->mode_name]);

        return $dataProvider;
    }
}
