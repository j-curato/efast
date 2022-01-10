<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrProjectProcurement;

/**
 * PrProjectProcurementSearch represents the model behind the search form of `app\models\PrProjectProcurement`.
 */
class PrProjectProcurementSearch extends PrProjectProcurement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pr_office_id', 'employee_id'], 'integer'],
            [['title'], 'safe'],
            [['amount'], 'number'],
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
        $query = PrProjectProcurement::find();

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
            'pr_office_id' => $this->pr_office_id,
            'amount' => $this->amount,
            'employee_id' => $this->employee_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
