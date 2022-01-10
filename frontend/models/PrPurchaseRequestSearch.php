<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrPurchaseRequest;

/**
 * PrPurchaseRequestSearch represents the model behind the search form of `app\models\PrPurchaseRequest`.
 */
class PrPurchaseRequestSearch extends PrPurchaseRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'book_id', 'pr_project_procurement_id', 'requested_by_id', 'approved_by_id'], 'integer'],
            [['pr_number', 'date', 'purpose', 'created_at'], 'safe'],
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
        $query = PrPurchaseRequest::find();

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
            'date' => $this->date,
            'book_id' => $this->book_id,
            'pr_project_procurement_id' => $this->pr_project_procurement_id,
            'requested_by_id' => $this->requested_by_id,
            'approved_by_id' => $this->approved_by_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'pr_number', $this->pr_number])
            ->andFilterWhere(['like', 'purpose', $this->purpose]);

        return $dataProvider;
    }
}
