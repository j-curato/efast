<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwJevPreparationIndexView;

/**
 * VwJevPreparationIndexViewSearch represents the model behind the search form of `app\models\VwJevPreparationIndexView`.
 */
class VwJevPreparationIndexViewSearch extends VwJevPreparationIndexView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [


            [['id'], 'integer'],
            [[
                'dv_number',
                'date',
                'reporting_period',
                'entry_type',
                'reference_type',
                'res_center',
                'book_name',
                'payee',
                'check_ada',
                'explaination',
                'dv_number',
                'jev_number',
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
        $query = VwJevPreparationIndexView::find();

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

        $query->andFilterWhere(['like', 'dv_number', $this->dv_number]);
        $query->andFilterWhere(['like', 'date', $this->date]);
        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period]);
        $query->andFilterWhere(['like', 'entry_type', $this->entry_type]);
        $query->andFilterWhere(['like', 'reference_type', $this->reference_type]);
        $query->andFilterWhere(['like', 'res_center', $this->res_center]);
        $query->andFilterWhere(['like', 'book_name', $this->book_name]);
        $query->andFilterWhere(['like', 'payee', $this->payee]);
        $query->andFilterWhere(['like', 'check_ada', $this->check_ada]);
        $query->andFilterWhere(['like', 'explaination', $this->explaination]);
        $query->andFilterWhere(['like', 'jev_number', $this->jev_number]);

        return $dataProvider;
    }
}
