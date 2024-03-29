<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProcessOrsEntries;

/**
 * ProcessOrsEntriesSearch represents the model behind the search form of `app\models\ProcessOrsEntries`.
 */
class ProcessOrsEntriesSearch extends ProcessOrsEntries
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'chart_of_account_id', 'process_ors_id'], 'integer'],
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
        $query = ProcessOrsEntries::find();
        // $query= Raouds::find()->where("process_ors_id IS NOT NULL");

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
            'chart_of_account_id' => $this->chart_of_account_id,
            'process_ors_id' => $this->process_ors_id,
            'amount' => $this->amount,
        ]);

        return $dataProvider;
    }
}
