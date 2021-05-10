<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LiquidationEntries;

/**
 * LiquidationEntriesSearch represents the model behind the search form of `app\models\LiquidationEntries`.
 */
class LiquidationEntriesSearch extends LiquidationEntries
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'liquidation_id', 'chart_of_account_id', 'advances_id'], 'integer'],
            [['withdrawals', 'vat_nonvat', 'ewt_goods_services'], 'number'],
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
        $query = LiquidationEntries::find();

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
            'liquidation_id' => $this->liquidation_id,
            'chart_of_account_id' => $this->chart_of_account_id,
            'advances_id' => $this->advances_id,
            'withdrawals' => $this->withdrawals,
            'vat_nonvat' => $this->vat_nonvat,
            'ewt_goods_services' => $this->ewt_goods_services,
        ]);

        return $dataProvider;
    }
}
