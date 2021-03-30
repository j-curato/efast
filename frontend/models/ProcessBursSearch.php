<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProcessBurs;

/**
 * ProcessBursSearch represents the model behind the search form of `app\models\ProcessBurs`.
 */
class ProcessBursSearch extends ProcessBurs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'transaction_id', 'document_recieve_id', 'mfo_pap_code_id', 'fund_source_id'], 'integer'],
            [['reporting_period', 'serial_number', 'obligation_number', 'funding_code'], 'safe'],
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
        $query = ProcessBurs::find();
        // $query= Raouds::find()->where("process_burs_id IS NOT NULL");

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
            'transaction_id' => $this->transaction_id,
            'document_recieve_id' => $this->document_recieve_id,
            'mfo_pap_code_id' => $this->mfo_pap_code_id,
            'fund_source_id' => $this->fund_source_id,
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'obligation_number', $this->obligation_number])
            ->andFilterWhere(['like', 'funding_code', $this->funding_code]);

        return $dataProvider;
    }
}
