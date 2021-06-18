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
            [['id', 'chart_of_account_id', ], 'integer'],
            [['withdrawals', 'vat_nonvat', 'expanded_tax'], 'number'],
            [['liquidation_id', ], 'safe'],
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
        $query->joinWith('liquidation');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'chart_of_account_id' => $this->chart_of_account_id,
            'withdrawals' => $this->withdrawals,
            'vat_nonvat' => $this->vat_nonvat,
            'expanded_tax' => $this->expanded_tax,
        ]);
        $query->andFilterWhere(['like','liquidation.dv_number',$this->liquidation_id]);

        return $dataProvider;
    }
}
