<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdvancesView;
use Yii;

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
                'check_date',
                'check_number', 'dv_number', 'particular', 'reporting_period',
                'nft_number',
                // 'r_center_name',
                'mode_of_payment',
                'payee',
                'particular',
                'book_name',
                'fund_source',
                'report_type',
                'object_code',
                'account_title',
                'fund_source_type',
                'bank_account',
                'province'

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
        $province = Yii::$app->user->identity->province;
        $query = AdvancesView::find();
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('province = :province', ['province' => $user_data->office->office_name]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['reporting_period' => SORT_DESC]],
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
            // ->andFilterWhere(['like', 'r_center_name', $this->r_center_name])
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
            ->andFilterWhere(['like', 'fund_source_type', $this->fund_source_type])
            ->andFilterWhere(['like', 'report_type', $this->report_type])
            ->andFilterWhere(['like', 'object_code', $this->object_code])
            ->andFilterWhere(['like', 'account_title', $this->account_title])
            ->andFilterWhere(['like', 'object_code', $this->object_code])
            ->andFilterWhere(['like', 'bank_account', $this->bank_account])
            ->andFilterWhere(['like', 'check_date', $this->check_date])
        ;

        return $dataProvider;
    }
}
