<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GeneralLedger;

/**
 * GeneralLedgerSearch represents the model behind the search form of `app\models\GeneralLedger`.
 */
class GeneralLedgerSearch extends GeneralLedger
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reporting_period', 'object_code', 'created_at', 'book_id'], 'safe'],
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
        $query = GeneralLedger::find();

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
        $query->joinWith('generalLedger');
        // grid filtering conditions
        $query->andFilterWhere([
            'general_ledger.id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['or', ['like', 'accounting_codes.object_code', $this->object_code], ['like', 'accounting_codes.account_title', $this->object_code]])
            ->andFilterWhere(['like', 'books.name', $this->book_id]);

        return $dataProvider;
    }
}
