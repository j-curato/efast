<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RecordAllotmentDetailed;
use Yii;

/**
 * RecordAllotmentDetailedSearch represents the model behind the search form of `app\models\RecordAllotmentDetailed`.
 */
class RecordAllotmentDetailedSearch extends RecordAllotmentDetailed
{
    public $budget_year = '';
    public $module = '';
    public $bookFilter = '';
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [[
                'allotmentNumber',
                'office_name',
                'division',
                'mfo_name',
                'fund_source_name',
                'account_title',
                'book_name',
                'reporting_period',
                'date_issued',
                'valid_until',
                'particulars',
                'document_recieve',
                'fund_cluster_code',
                'financing_source_code',
                'fund_classification',
                'authorization_code',
                'responsibility_center',
                'allotment_class',
                'nca_nta',
                'carp_101',
                'book',
                'allotment_type',
                'budget_year',
                'bookFilter',
                'module',
                'uacs'

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
        $query = RecordAllotmentDetailed::find();
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('office_name = :office_name', ['office_name' => $user_data->office->office_name]);
            $query->andWhere('division = :division', ['division' => $user_data->divisionName->division]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['budget_year' => SORT_DESC]],
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
            ->andFilterWhere(['like', 'allotmentNumber', $this->allotmentNumber])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'mfo_name', $this->mfo_name])
            ->andFilterWhere(['like', 'fund_source_name', $this->fund_source_name])
            ->andFilterWhere(['like', 'account_title', $this->account_title])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'date_issued', $this->date_issued])
            ->andFilterWhere(['like', 'particulars', $this->particulars])
            ->andFilterWhere(['like', 'document_recieve', $this->document_recieve])
            ->andFilterWhere(['like', 'fund_cluster_code', $this->fund_cluster_code])
            ->andFilterWhere(['like', 'financing_source_code', $this->financing_source_code])
            ->andFilterWhere(['like', 'fund_classification', $this->fund_classification])
            ->andFilterWhere(['like', 'authorization_code', $this->authorization_code])
            ->andFilterWhere(['like', 'responsibility_center', $this->responsibility_center])
            ->andFilterWhere(['like', 'allotment_class', $this->allotment_class])
            ->andFilterWhere(['like', 'nca_nta', $this->nca_nta])
            ->andFilterWhere(['like', 'carp_101', $this->carp_101])
            ->andFilterWhere(['like', 'book', $this->book])
            ->andFilterWhere(['like', 'budget_year', $this->budget_year])
            ->andFilterWhere(['like', 'uacs', $this->uacs])
            ->andFilterWhere(['like', 'allotment_type', $this->allotment_type]);





        if ($this->module === 'transaction') {
            $query->andWhere(['=', 'book_name', $this->bookFilter]);
        }
        return $dataProvider;
    }
}
