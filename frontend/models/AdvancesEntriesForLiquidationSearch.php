<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdvancesEntriesForLiquidation;

/**
 * AdvancesEntriesForLiquidationSearch represents the model behind the search form of `app\models\AdvancesEntriesForLiquidation`.
 */
class AdvancesEntriesForLiquidationSearch extends AdvancesEntriesForLiquidation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $province = 'province';
        if (\Yii::$app->user->identity->province != '') {
            $province = '';
        }
        return [
            [['id'], 'integer'],


            [['amount', 'balance'], 'number'],
            [['fund_source', 'total_liquidation', 'reporting_period', 'particular', 'bank_account_name', 'bank_account_id','book_name'], 'safe'],
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
        $query = AdvancesEntriesForLiquidation::find()->where('balance > 0 ');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['balance' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);


        $query->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'fund_source', $this->fund_source])
            ->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'balance', $this->balance])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'bank_account_id', $this->bank_account_id])
            ->andFilterWhere(['like', 'bank_account_name', $this->bank_account_name])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'total_liquidation', $this->total_liquidation]);
        // var_dump( $query->createCommand()->getRawSql());
        // die();
        return $dataProvider;
    }
}
