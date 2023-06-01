<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwCashReceived;

/**
 * VwCashReceivedSearch represents the model behind the search form of `app\models\VwCashReceived`.
 */
class VwCashReceivedSearch extends VwCashReceived
{
    public $bookFilter = '';
    public $validityFilter = '';
    public $type = 'cash_receive';
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['amount'], 'number'],
            [[
                'date',
                'reporting_period',
                'valid_from',
                'valid_to',
                'purpose',
                'nca_no',
                'nta_no',
                'document_receive_name',
                'book_name',
                'mfo_name',
                'bookFilter',
                'validityFilter',
            ], 'safe'],


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
        $query = VwCashReceived::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if ($this->type != 'cash_receive') {

            if (empty($this->bookFilter) || empty($this->validityFilter)) {
                $query->where('0=1');
                return $dataProvider;
            } else {
                $query->andFilterWhere(['like', 'book_name', $this->bookFilter]);
                $query->andWhere(":dt BETWEEN vw_cash_received.valid_from AND vw_cash_received.valid_to", [':dt' => $this->validityFilter]);
            
                // echo $query->createCommand()->getRawSql();
                // die();
            }
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'valid_from', $this->valid_from])
            ->andFilterWhere(['like', 'valid_to', $this->valid_to])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'nca_no', $this->nca_no])
            ->andFilterWhere(['like', 'nta_no', $this->nta_no])
            ->andFilterWhere(['like', 'document_receive_name', $this->document_receive_name])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'mfo_name', $this->mfo_name]);

        return $dataProvider;
    }
}
