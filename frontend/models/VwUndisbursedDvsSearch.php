<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwUndisbursedDvs;

/**
 * VwUndisbursedDvsSearch represents the model behind the search form of `app\models\VwUndisbursedDvs`.
 */
class VwUndisbursedDvsSearch extends VwUndisbursedDvs
{
    public $bookFilter;
    public $pageSize = 10;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'ttlAmtDisbursed',
                'ttlTax',
                'grossAmt',
            ], 'number'],
            [[
                'id',
                'is_cancelled'
            ], 'integer'],
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
                'bookFilter',
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
        $query = VwUndisbursedDvs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize, // Set the page size
            ],
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
            'is_cancelled' => $this->is_cancelled
        ]);

        $query->andFilterWhere(['like', 'ttlAmtDisbursed', $this->ttlAmtDisbursed])
            ->andFilterWhere(['like', 'ttlTax', $this->ttlTax])
            ->andFilterWhere(['like', 'grossAmt', $this->grossAmt])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'natureOfTxn', $this->natureOfTxn])
            ->andFilterWhere(['like', 'mrdName', $this->mrdName])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['=', 'book_name', $this->bookFilter])
            ->andFilterWhere(['like', 'orsNums', $this->orsNums])
            ->andFilterWhere(['like', 'txnType', $this->txnType]);

        return $dataProvider;
    }
}
