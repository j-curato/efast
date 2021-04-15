<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JevAccountingEntries;

/**
 * JevAccountingEntriesSearch represents the model behind the search form of `app\models\JevAccountingEntries`.
 */
class JevAccountingEntriesSearch extends JevAccountingEntries
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'jev_preparation_id', 'chart_of_account_id'], 'integer'],
            [['debit', 'credit'], 'number'],
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
        $query = JevAccountingEntries::find();

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
        $query->joinWith("jevPreparation");

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'jev_preparation_id' => $this->jev_preparation_id,
            'chart_of_account_id' => $this->chart_of_account_id,
            'debit' => $this->debit,
            'credit' => $this->credit,
        ]);
        // $query->andFilterWhere(['like', 'jev_preparation.book_id', $book_id])
        //     ->andFilterWhere(['like', 'jev_preparation.reporting_period', $reporting_period]);

        return $dataProvider;
    }
}
