<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwTransactionstracking;
use common\models\User;
use Yii;

/**
 * VwTransactionstrackingSearch represents the model behind the search form of `app\models\VwTransactionstracking`.
 */
class VwTransactionstrackingSearch extends VwTransactionstracking
{
    public $bookFilter;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [[
                'transactionNum',
                'transactionDate',
                'responsibilityCenter',
                'payee',
                'orsNum',
                'dv_number',
                'checkNum',
                'adaNum',
                'cashIsCancelled',
                'acicNum',
                'acicInBankNum',
                'acicInBankDate',
                'dvStatus',
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
        $query = VwTransactionstracking::find();
        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = User::getUserDetails();
            $query->where('responsibilityCenter = :division', ['division' => $user_data->employee->empDivision->division]);
        }
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
        $query->andFilterWhere([]);

        $query
            ->andFilterWhere(['like', 'transactionNum', $this->transactionNum])
            ->andFilterWhere(['like', 'transactionDate', $this->transactionDate])
            ->andFilterWhere(['like', 'responsibilityCenter', $this->responsibilityCenter])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'orsNum', $this->orsNum])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'checkNum', $this->checkNum])
            ->andFilterWhere(['like', 'adaNum', $this->adaNum])
            ->andFilterWhere(['like', 'cashIsCancelled', $this->cashIsCancelled])
            ->andFilterWhere(['like', 'acicNum', $this->acicNum])
            ->andFilterWhere(['like', 'acicInBankNum', $this->acicInBankNum])
            ->andFilterWhere(['like', 'acicInBankDate', $this->acicInBankDate])
            ->andFilterWhere(['like', 'dvStatus', $this->dvStatus]);

        return $dataProvider;
    }
}
