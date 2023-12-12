<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RapidMgDatabase;

/**
 * RapidMgDatabaseSearch represents the model behind the search form of `app\models\RapidMgDatabase`.
 */
class RapidMgDatabaseSearch extends RapidMgDatabase
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'notification_to_pay_count',
                'due_diligence_report_count',
            ], 'integer'],
            [[
                'total_deposit_equity',
                'total_deposit_grant',
                'total_deposit_other_amount',
                'total_liquidation_grant',
                'total_liquidation_equity',
                'total_liquidation_other_amount',
                'balance_equity',
                'balance_grant',
                'balance_other_amount',
            ], 'number'],
            [[
                'office_name',
                'province_name',
                'municipality_name',
                'barangay_name',
                'organization_name',
                'purok',
                'authorized_personnel',
                'contact_number',
                'saving_account_number',
                'email_address',
                'investment_type',
                'investment_description',
                'project_consultant',
                'project_objective',
                'project_beneficiary',
                'matching_grant_amount',
                'equity_amount',
                'bank_manager',
                'address',
                'bank_name',

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
        $query = RapidMgDatabase::find();

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
            'notification_to_pay_count' => $this->notification_to_pay_count,
            'due_diligence_report_count' => $this->due_diligence_report_count,
            'total_deposit_equity' => $this->total_deposit_equity,
            'total_deposit_grant' => $this->total_deposit_grant,
            'total_deposit_other_amount' => $this->total_deposit_other_amount,
            'total_liquidation_grant' => $this->total_liquidation_grant,
            'total_liquidation_equity' => $this->total_liquidation_equity,
            'total_liquidation_other_amount' => $this->total_liquidation_other_amount,
            'balance_equity' => $this->balance_equity,
            'balance_grant' => $this->balance_grant,
            'balance_other_amount' => $this->balance_other_amount,

        ]);
        $query
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'province_name', $this->province_name])
            ->andFilterWhere(['like', 'municipality_name', $this->municipality_name])
            ->andFilterWhere(['like', 'barangay_name', $this->barangay_name])
            ->andFilterWhere(['like', 'organization_name', $this->organization_name])
            ->andFilterWhere(['like', 'purok', $this->purok])
            ->andFilterWhere(['like', 'authorized_personnel', $this->authorized_personnel])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'saving_account_number', $this->saving_account_number])
            ->andFilterWhere(['like', 'email_address', $this->email_address])
            ->andFilterWhere(['like', 'investment_type', $this->investment_type])
            ->andFilterWhere(['like', 'investment_description', $this->investment_description])
            ->andFilterWhere(['like', 'project_consultant', $this->project_consultant])
            ->andFilterWhere(['like', 'project_objective', $this->project_objective])
            ->andFilterWhere(['like', 'project_beneficiary', $this->project_beneficiary])
            ->andFilterWhere(['like', 'matching_grant_amount', $this->matching_grant_amount])
            ->andFilterWhere(['like', 'equity_amount', $this->equity_amount])
            ->andFilterWhere(['like', 'bank_manager', $this->bank_manager])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name]);



        return $dataProvider;
    }
}
