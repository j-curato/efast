<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RecordAllotments;

/**
 * RecordAllotmentsSearch represents the model behind the search form of `app\models\RecordAllotments`.
 */
class RecordAllotmentsSearch extends RecordAllotments
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_recieve_id', 'fund_cluster_code_id', 'financing_source_code_id', 'fund_category_and_classification_code_id', 'authorization_code_id', 'mfo_pap_code_id', 'fund_source_id'], 'integer'],
            [['reporting_period', 'serial_number', 'date_issued', 'valid_until', 'particulars'], 'safe'],
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
        $query = RecordAllotments::find();

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
            'document_recieve_id' => $this->document_recieve_id,
            'fund_cluster_code_id' => $this->fund_cluster_code_id,
            'financing_source_code_id' => $this->financing_source_code_id,
            'fund_category_and_classification_code_id' => $this->fund_category_and_classification_code_id,
            'authorization_code_id' => $this->authorization_code_id,
            'mfo_pap_code_id' => $this->mfo_pap_code_id,
            'fund_source_id' => $this->fund_source_id,
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'date_issued', $this->date_issued])
            ->andFilterWhere(['like', 'valid_until', $this->valid_until])
            ->andFilterWhere(['like', 'particulars', $this->particulars]);

        return $dataProvider;
    }
}
