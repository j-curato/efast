<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AllotmentModificationAdvice;

/**
 * AllotmentModificationAdviceSearch represents the model behind the search form of `app\models\AllotmentModificationAdvice`.
 */
class AllotmentModificationAdviceSearch extends AllotmentModificationAdvice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_book_id', 'fk_allotment_type_id', 'fk_mfo_pap_id', 'fk_document_receive_id', 'fk_fund_source'], 'integer'],
            [['date', 'particulars', 'reporting_period', 'created_at'], 'safe'],
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
        $query = AllotmentModificationAdvice::find();

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
            'date' => $this->date,
            'fk_book_id' => $this->fk_book_id,
            'fk_allotment_type_id' => $this->fk_allotment_type_id,
            'fk_mfo_pap_id' => $this->fk_mfo_pap_id,
            'fk_document_receive_id' => $this->fk_document_receive_id,
            'fk_fund_source' => $this->fk_fund_source,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'particulars', $this->particulars])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period]);

        return $dataProvider;
    }
}
