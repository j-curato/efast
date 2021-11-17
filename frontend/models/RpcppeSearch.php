<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rpcppe;

/**
 * RpcppeSearch represents the model behind the search form of `app\models\Rpcppe`.
 */
class RpcppeSearch extends Rpcppe
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rpcppe_number', 'reporting_period', 'certified_by', 'approved_by', 'verified_by', 'verified_pos'], 'safe'],
            [['book_id'], 'integer'],
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
        $query = Rpcppe::find();

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
            'book_id' => $this->book_id,
        ]);

        $query->andFilterWhere(['like', 'rpcppe_number', $this->rpcppe_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'certified_by', $this->certified_by])
            ->andFilterWhere(['like', 'approved_by', $this->approved_by])
            ->andFilterWhere(['like', 'verified_by', $this->verified_by])
            ->andFilterWhere(['like', 'verified_pos', $this->verified_pos]);

        return $dataProvider;
    }
}
