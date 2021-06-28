<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RecordAllotmentEntries;

/**
 * recordAllotmentEntriesSearch represents the model behind the search form of `app\models\recordAllotmentEntries`.
 */
class recordAllotmentEntriesSearch extends RecordAllotmentEntries
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'record_allotment_id', 'chart_of_account_id', 'lvl'], 'integer'],
            [['amount'], 'number'],
            [['object_code'], 'safe'],
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
        $query = RecordAllotmentEntries::find();

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
        $query->joinWith("recordAllotment");
        // grid filtering conditions
        $query->andFilterWhere([
            'record_allotment_entries.id' => $this->id,
            // 'record_allotment_id' => $this->record_allotment_id,
            'chart_of_account_id' => $this->chart_of_account_id,
            'amount' => $this->amount,
            'lvl' => $this->lvl,
        ]);

        $query->andFilterWhere(['like', 'object_code', $this->object_code])
            ->andFilterWhere(['like', 'record_allotments.serial_number', $this->record_allotment_id]);

 ;       return $dataProvider;
    }
}
