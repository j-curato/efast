<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrIarItem;

/**
 * PrIarItemSearch represents the model behind the search form of `app\models\PrIarItem`.
 */
class PrIarItemSearch extends PrIarItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_pr_iar_id', 'quantity', 'fk_pr_aoq_entry_id'], 'integer'],
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
        $query = PrIarItem::find();

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
            'fk_pr_iar_id' => $this->fk_pr_iar_id,
            'quantity' => $this->quantity,
            'fk_pr_aoq_entry_id' => $this->fk_pr_aoq_entry_id,
        ]);

        return $dataProvider;
    }
}
