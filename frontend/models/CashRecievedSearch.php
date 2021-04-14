<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CashRecieved;

/**
 * CashRecievedSearch represents the model behind the search form of `app\models\CashRecieved`.
 */
class CashRecievedSearch extends CashRecieved
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_recieved_id', 'book_id', 'mfo_pap_code_id'], 'integer'],
            [['date', 'reporting_period', 'nca_no', 'nta_no', 'nft_no', 'purpose'], 'safe'],
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
        $query = CashRecieved::find();

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
            'document_recieved_id' => $this->document_recieved_id,
            'book_id' => $this->book_id,
            'mfo_pap_code_id' => $this->mfo_pap_code_id,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'nca_no', $this->nca_no])
            ->andFilterWhere(['like', 'nta_no', $this->nta_no])
            ->andFilterWhere(['like', 'nft_no', $this->nft_no])
            ->andFilterWhere(['like', 'purpose', $this->purpose]);

        return $dataProvider;
    }
}
