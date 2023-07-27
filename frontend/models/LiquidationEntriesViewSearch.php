<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LiquidationEntriesView;
use Yii;

/**
 * LiquidationEntriesViewSearch represents the model behind the search form of `app\models\LiquidationEntriesView`.
 */
class LiquidationEntriesViewSearch extends LiquidationEntriesView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {


        return [
            [[
                'dv_number',
                'check_date',
                'check_number',
                'fund_source',
                'particular',
                'payee',
                'object_code',
                'account_title',
                'withdrawals',
                'vat_nonvat',
                'expanded_tax',
                'liquidation_damage',
                'gross_payment',
                'reporting_period',

                'province'
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

        $q = LiquidationEntriesView::find();

        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = Yii::$app->memem->getUserData();
            $q->where('province LIKE :province', ['province' => strtolower($user_data->office->office_name)]);
        }
        $query = $q;

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
        //     'id' => $this->id,
        //     'is_locked' => $this->is_locked,
        // ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'check_date', $this->check_date])
            ->andFilterWhere(['like', 'check_number', $this->check_number])
            ->andFilterWhere(['like', 'fund_source', $this->fund_source])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'object_code', $this->object_code])
            ->andFilterWhere(['like', 'account_title', $this->account_title])
            ->andFilterWhere(['like', 'withdrawals', $this->withdrawals])
            ->andFilterWhere(['like', 'vat_nonvat', $this->vat_nonvat])
            ->andFilterWhere(['like', 'expanded_tax', $this->expanded_tax])
            ->andFilterWhere(['like', 'liquidation_damage', $this->liquidation_damage])
            ->andFilterWhere(['like', 'gross_payment', $this->gross_payment])
            ->andFilterWhere(['like', 'province', $this->province]);

        return $dataProvider;
    }
}
