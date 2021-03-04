<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CashFlow;

/**
 * CashFlowSearch represents the model behind the search form of `app\models\CashFlow`.
 */
class CashFlowSearch extends CashFlow
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['major_cashflow', 'sub_cashflow1', 'sub_cashflow2', 'specific_cashflow'], 'safe'],
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
        $query = CashFlow::find();

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

        $query->andFilterWhere(['like', 'major_cashflow', $this->major_cashflow])
            ->andFilterWhere(['like', 'sub_cashflow1', $this->sub_cashflow1])
            ->andFilterWhere(['like', 'sub_cashflow2', $this->sub_cashflow2])
            ->andFilterWhere(['like', 'specific_cashflow', $this->specific_cashflow]);

        return $dataProvider;
    }
}
