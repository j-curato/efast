<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DvAucsIndex;

/**
 * DvAucsIndexSearch represents the model behind the search form of `app\models\DvAucsIndex`.
 */
class DvAucsIndexSearch extends DvAucsIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',  'is_cancelled',], 'integer'],
            [[
                'ttlAmtDisbursed',
                'ttlTax',
                'grossAmt',
            ], 'number'],
            [[
                'dv_number',
                'reporting_period',
                'particular',
                'natureOfTxn',
                'mrdName',
                'payee',
                'book_name',
                'orsNums',
                'txnType',
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
        $query = DvAucsIndex::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);



        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_cancelled' => $this->is_cancelled
        ]);

        $query
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'natureOfTxn', $this->natureOfTxn])
            ->andFilterWhere(['like', 'mrdName', $this->mrdName])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'orsNums', $this->orsNums])
            ->andFilterWhere(['like', 'txnType', $this->txnType])
            ->andFilterWhere(['like', 'ttlAmtDisbursed', $this->ttlAmtDisbursed])
            ->andFilterWhere(['like', 'ttlTax', $this->ttlTax])
            ->andFilterWhere(['like', 'grossAmt', $this->grossAmt]);




        return $dataProvider;
    }
}
