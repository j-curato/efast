<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdvancesEntries;

/**
 * AdvancesEntriesSearch represents the model behind the search form of `app\models\AdvancesEntries`.
 */
class AdvancesEntriesSearch extends AdvancesEntries
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'advances_id', 'cash_disbursement_id', 'sub_account1_id'], 'integer'],
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
        $query = AdvancesEntries::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
            
        $this->load($params,'');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('advances');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'advances_id' => $this->advances_id,
            'cash_disbursement_id' => $this->cash_disbursement_id,
            'sub_account1_id' => $this->sub_account1_id,
            'amount' => $this->amount,
        ]);

        return $dataProvider;
    }
}
