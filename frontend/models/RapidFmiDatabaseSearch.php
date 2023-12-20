<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RapidFmiDatabase;

/**
 * RapidFmiDatabaseSearch represents the model behind the search form of `app\models\RapidFmiDatabase`.
 */
class RapidFmiDatabaseSearch extends RapidFmiDatabase
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'province_name',
                'municipality_name',
                'barangay_name',
                'purok',
                'batch_name',
                'project_duration',
                'project_road_length',
                'project_start_date',
                'bank_account_name',
                'bank_account_number',
                'project_name',
                'bank_manager',
                'address',
                'branch_name',
                'bank_name',
                'total_grant_deposit',
                'total_deposit_equity',
                'total_deposit_other',
                'total_liquidated_equity',
                'total_liquidated_grant',
                'total_liquidated_other',
                'grant_beginning_balance',
                'equity_beginning_balance',
                'other_beginning_balance',
                'bank_certification_link',
                'certificate_of_project_link',
                'certificate_of_turnover_link',
                'spcr_link',
                'serial_number',

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
        $query = RapidFmiDatabase::find();

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
            // 'id' => $this->id
        ]);

        $query->andFilterWhere(['like', 'province_name', $this->province_name])
            ->andFilterWhere(['like', 'municipality_name', $this->municipality_name])
            ->andFilterWhere(['like', 'barangay_name', $this->barangay_name])
            ->andFilterWhere(['like', 'purok', $this->purok])
            ->andFilterWhere(['like', 'batch_name', $this->batch_name])
            ->andFilterWhere(['like', 'project_duration', $this->project_duration])
            ->andFilterWhere(['like', 'project_road_length', $this->project_road_length])
            ->andFilterWhere(['like', 'project_start_date', $this->project_start_date])
            ->andFilterWhere(['like', 'bank_account_name', $this->bank_account_name])
            ->andFilterWhere(['like', 'bank_account_number', $this->bank_account_number])
            ->andFilterWhere(['like', 'project_name', $this->project_name])
            ->andFilterWhere(['like', 'bank_manager', $this->bank_manager])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'branch_name', $this->branch_name])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'total_grant_deposit', $this->total_grant_deposit])
            ->andFilterWhere(['like', 'total_deposit_equity', $this->total_deposit_equity])
            ->andFilterWhere(['like', 'total_deposit_other', $this->total_deposit_other])
            ->andFilterWhere(['like', 'total_liquidated_equity', $this->total_liquidated_equity])
            ->andFilterWhere(['like', 'total_liquidated_grant', $this->total_liquidated_grant])
            ->andFilterWhere(['like', 'total_liquidated_other', $this->total_liquidated_other])
            ->andFilterWhere(['like', 'grant_beginning_balance', $this->grant_beginning_balance])
            ->andFilterWhere(['like', 'equity_beginning_balance', $this->equity_beginning_balance])
            ->andFilterWhere(['like', 'other_beginning_balance', $this->other_beginning_balance])
            ->andFilterWhere(['like', 'bank_certification_link', $this->bank_certification_link])
            ->andFilterWhere(['like', 'certificate_of_project_link', $this->certificate_of_project_link])
            ->andFilterWhere(['like', 'certificate_of_turnover_link', $this->certificate_of_turnover_link])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'spcr_link', $this->spcr_link]);



        return $dataProvider;
    }
}
