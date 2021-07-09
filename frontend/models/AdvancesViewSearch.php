<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdvancesView;

/**
 * AdvancesViewSearch represents the model behind the search form of `app\models\AdvancesView`.
 */
class AdvancesViewSearch extends AdvancesView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [[
                'check_date', 'check_number', 'dv_number', 'particular', 'reporting_period',
                'nft_number',
                'r_center_name',
                'mode_of_payment',
                'payee',
                'particular',
                'book_name',
                'province',
                'fund_source',
                'report_type',
                'object_code',
                'account_title',
                'fund_source',

            ], 'safe'],
            [['total_liquidation'], 'number'],
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
        $query = AdvancesView::find();

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
        // $query->andFilterWhere([

        // ]);


        $query
            ->andFilterWhere(['like', 'nft_number', $this->nft_number])
            ->andFilterWhere(['like', 'r_center_name', $this->r_center_name])
            ->andFilterWhere(['like', 'mode_of_payment', $this->mode_of_payment])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'check_number', $this->check_number])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'total_liquidation', $this->total_liquidation])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'fund_source', $this->fund_source])
            ->andFilterWhere(['like', 'report_type', $this->report_type])
            ->andFilterWhere(['like', 'object_code', $this->object_code])
            ->andFilterWhere(['like', 'account_title', $this->account_title])
            ->andFilterWhere(['like', 'object_code', $this->object_code])
            ->andFilterWhere(['like', 'check_date', $this->check_date]);

        return $dataProvider;
    }
}
