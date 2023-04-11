<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DetailedPropertyDatabase;

/**
 * DetailedPropertyDatabaseSearch represents the model behind the search form of `app\models\DetailedPropertyDatabase`.
 */
class DetailedPropertyDatabaseSearch extends DetailedPropertyDatabase
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'pc_num',
                    'ptr_number',
                    'ptr_date',
                    'type',
                    'derecognition_num',
                    'derecognition_date',
                    'property_number',
                    'date_acquired',
                    'serial_number',
                    'article',
                    'description',
                    'acquisition_amount',
                    'unit_of_measure',
                    'useful_life',
                    'strt_mnth',
                    'lst_mth',
                    'new_last_month',
                    'sec_lst_mth',
                    'par_number',
                    'par_date',
                    'rcv_by',
                    'act_usr',
                    'isd_by',
                    'office_name',
                    'division',
                    'location',
                    'isCrntUsr',
                    'isUnserviceable',
                    'is_current_user',
                    'uacs',
                    'general_ledger',
                    'depreciation_account_title',
                    'depreciation_object_code',
                ], 'safe'
            ],
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
        $query = DetailedPropertyDatabase::find();

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




        $query->andFilterWhere(['like', 'pc_num', $this->pc_num])
            ->andFilterWhere(['like', 'ptr_number', $this->ptr_number])
            ->andFilterWhere(['like', 'ptr_date', $this->ptr_date])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'depreciation_object_code', $this->depreciation_object_code])
            ->andFilterWhere(['like', 'depreciation_account_title', $this->depreciation_account_title])
            ->andFilterWhere(['like', 'general_ledger', $this->general_ledger])
            ->andFilterWhere(['like', 'uacs', $this->uacs])
            ->andFilterWhere(['like', 'isUnserviceable', $this->isUnserviceable])
            ->andFilterWhere(['like', 'isCrntUsr', $this->isCrntUsr])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'isd_by', $this->isd_by])
            ->andFilterWhere(['like', 'act_usr', $this->act_usr])
            ->andFilterWhere(['like', 'rcv_by', $this->rcv_by])
            ->andFilterWhere(['like', 'par_date', $this->par_date])
            ->andFilterWhere(['like', 'par_number', $this->par_number])
            ->andFilterWhere(['like', 'sec_lst_mth', $this->sec_lst_mth])
            ->andFilterWhere(['like', 'new_last_month', $this->new_last_month])
            ->andFilterWhere(['like', 'lst_mth', $this->lst_mth])
            ->andFilterWhere(['like', 'strt_mnth', $this->strt_mnth])
            ->andFilterWhere(['like', 'useful_life', $this->useful_life])
            ->andFilterWhere(['like', 'unit_of_measure', $this->unit_of_measure])
            ->andFilterWhere(['like', 'acquisition_amount', $this->acquisition_amount])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'date_acquired', $this->date_acquired])
            ->andFilterWhere(['like', 'property_number', $this->property_number])
            ->andFilterWhere(['like', 'derecognition_num', $this->derecognition_num])
            ->andFilterWhere(['like', 'derecognition_date', $this->derecognition_date]);

        return $dataProvider;
    }
}
