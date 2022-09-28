<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ParIndex;

/**
 * ParIndexSearch represents the model behind the search form of `app\models\ParIndex`.
 */
class ParIndexSearch extends ParIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'recieved_by',
                    'actual_user',
                    'date',
                    'par_number',
                    'property_number',
                    'unit_of_measure',
                    'book_name',
                    'article',
                    'iar_number',
                    'description',
                    'acquisition_amount',
                ],
                'safe'
            ],
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
        $query = ParIndex::find();

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

        $query->andFilterWhere(['like', 'par_number', $this->par_number])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'property_number', $this->property_number])
            ->andFilterWhere(['like', 'actual_user', $this->actual_user])
            ->andFilterWhere(['like', 'recieved_by', $this->recieved_by])
            ->andFilterWhere(['like', 'unit_of_measure', $this->unit_of_measure])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'iar_number', $this->iar_number])
            ->andFilterWhere(['like', 'book_name', $this->book_name]);

        return $dataProvider;
    }
}
