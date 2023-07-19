<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwNoPoTransmittalLiqs;
use Yii;

/**
 * VwNoPoTransmittalLiqsSearch represents the model behind the search form of `app\models\VwNoPoTransmittalLiqs`.
 */
class VwNoPoTransmittalLiqsSearch extends VwNoPoTransmittalLiqs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],

            [[
                'check_date',
                'check_number',
                'dv_number',
                'reporting_period',
                'payee',
                'particular',
                'account_name',
                'total_withdrawal',
                'total_expanded',
                'total_liquidation_damage',
                'total_vat',
                'gross_payment',
                'province',
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
        $query = VwNoPoTransmittalLiqs::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('province = :province', ['province' => $user_data->office->office_name]);
        }
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,

        ]);

        $query->andFilterWhere(['like', 'check_date', $this->check_date])
            ->andFilterWhere(['like', 'check_number', $this->check_number])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'account_name', $this->account_name])
            ->andFilterWhere(['like', 'total_withdrawal', $this->total_withdrawal])
            ->andFilterWhere(['like', 'total_expanded', $this->total_expanded])
            ->andFilterWhere(['like', 'total_liquidation_damage', $this->total_liquidation_damage])
            ->andFilterWhere(['like', 'total_vat', $this->total_vat])
            ->andFilterWhere(['like', 'gross_payment', $this->gross_payment])
            ->andFilterWhere(['like', 'province', $this->province]);

        return $dataProvider;
    }
}
