<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Remittance;

/**
 * RemittanceSearch represents the model behind the search form of `app\models\Remittance`.
 */
class RemittanceSearch extends Remittance
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reporting_period', 'created_at','book_id','type','remittance_number'], 'safe'],
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
        $query = Remittance::find();

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
        $query->joinWith('book');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
        ->andFilterWhere(['like', 'books.name', $this->book_id])
        ->andFilterWhere(['like', 'remittance_number', $this->remittance_number])
        ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
