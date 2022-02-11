<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrAoq;

/**
 * PrAoqSearch represents the model behind the search form of `app\models\PrAoq`.
 */
class PrAoqSearch extends PrAoq
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pr_rfq_id'], 'integer'],
            [['aoq_number', 'pr_date', 'created_at'], 'safe'],
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
        $query = PrAoq::find();

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
            'pr_rfq_id' => $this->pr_rfq_id,
            'pr_date' => $this->pr_date,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'aoq_number', $this->aoq_number]);

        return $dataProvider;
    }
}
