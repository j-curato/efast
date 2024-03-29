<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Mgrfrs;
use common\models\User;
use yii\data\ActiveDataProvider;

/**
 * MgrfrsSearch represents the model behind the search form of `app\models\Mgrfrs`.
 */
class MgrfrsSearch extends Mgrfrs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_bank_branch_detail_id'], 'integer'],
            [[
                'purok', 'authorized_personnel', 'contact_number', 'saving_account_number',
                'email_address', 'investment_type', 'investment_description',
                'project_consultant', 'project_objective', 'project_beneficiary', 'created_at',
                'fk_municipality_id', 'fk_barangay_id', 'fk_office_id',
                'fk_province_id',
                'serial_number'
            ], 'safe'],
            [['matching_grant_amount', 'equity_amount'], 'number'],
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
        $query = Mgrfrs::find();

        // add conditions that should always apply here
        if (!Yii::$app->user->can('ro_rapid_fma')) {
            $user_data = User::getUserDetails();

            $query->andWhere(['fk_office_id' => $user_data->employee->office->id]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('office');
        $query->joinWith('municipality');
        $query->joinWith('barangay');
        $query->joinWith('province');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fk_bank_branch_detail_id' => $this->fk_bank_branch_detail_id,
            'matching_grant_amount' => $this->matching_grant_amount,
            'equity_amount' => $this->equity_amount,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'purok', $this->purok])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'authorized_personnel', $this->authorized_personnel])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'saving_account_number', $this->saving_account_number])
            ->andFilterWhere(['like', 'email_address', $this->email_address])
            ->andFilterWhere(['like', 'investment_type', $this->investment_type])
            ->andFilterWhere(['like', 'investment_description', $this->investment_description])
            ->andFilterWhere(['like', 'project_consultant', $this->project_consultant])
            ->andFilterWhere(['like', 'project_objective', $this->project_objective])
            ->andFilterWhere(['like', 'project_beneficiary', $this->project_beneficiary])
            ->andFilterWhere(['like', 'municipalities.municipality_name', $this->fk_municipality_id])
            ->andFilterWhere(['like', 'barangays.barangay_name', $this->fk_barangay_id])
            ->andFilterWhere(['like', 'office.office_name', $this->fk_office_id])
            ->andFilterWhere(['like', 'provinces.province_name', $this->fk_province_id]);





        return $dataProvider;
    }
}
