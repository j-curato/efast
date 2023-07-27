<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LiquidationView;
use Yii;

/**
 * LiquidationViewSearch represents the model behind the search form of `app\models\LiquidationView`.
 */
class LiquidationViewSearch extends LiquidationView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            [['id', 'is_cancelled'], 'integer'],
            [['total_withdrawal', 'total_expanded', 'total_liquidation_damage', 'total_vat'], 'number'],
            [[

                'check_date',
                'check_number',
                'dv_number',
                'reporting_period',
                'status',
                'tracking_number',
                'payee', 'particular', 'gross_payment',
                'account_name',
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

        $query = LiquidationView::find();
        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->where('province LIKE :province', ['province' => $user_data->office->office_name]);
        }

        $query->orderBy('check_date DESC');

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
            'total_withdrawal' => $this->total_withdrawal,
            'total_expanded' => $this->total_expanded,
            'total_liquidation_damage' => $this->total_liquidation_damage,
            'total_vat' => $this->total_vat,
            'is_cancelled' => $this->is_cancelled,
            'is_final' => $this->is_final
        ]);
        $query
            ->andFilterWhere(['or', ['like', 'payee', $this->payee], ['like', 'tr_payee', $this->payee]])
            ->andFilterWhere(['or', ['like', 'tr_particular', $this->particular], ['like', 'particular', $this->particular]])
            ->andFilterWhere(['like', 'check_date', $this->check_date])
            ->andFilterWhere(['like', 'check_number', $this->check_number])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'gross_payment', $this->gross_payment])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'account_name', $this->account_name])
            ->andFilterWhere(['like', 'province', $this->province]);

        // var_dump($query->createCommand()->getRawSql());

        // die();


        return $dataProvider;
    }
}
