<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RecordAllotmentsView;
use Yii;

/**
 * RecordAllotmentsViewSearch represents the model behind the search form of `app\models\RecordAllotmentsView`.
 */
class RecordAllotmentsViewSearch extends RecordAllotmentsView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [[
                'id',
                'entry_id'
            ], 'integer'],
            [[
                'amount',
                'total_ors',

            ], 'number'],


            [[
                'reporting_period',
                'serial_number',
                'date_issued',
                'valid_until',
                'particulars',
                'document_recieve',
                'fund_cluster_code',
                'financing_source_code',
                'fund_classification',
                'authorization_code',
                'mfo_code',
                'mfo_name',
                'responsibility_center',
                'fund_source',
                'uacs',
                'general_ledger',
                'allotment_class',
                'nca_nta',
                'carp_101',
                'office_name',
                'division',
                'allotment_type',
                'book'

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
    public function search($params, $type, $responsibility_center = '')
    {
        $query = RecordAllotmentsView::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($type === 'burs') {
            $query->andFilterWhere(['like', 'book', 'Fund 07']);
        } else if ($type === 'ors') {
            $query->andFilterWhere(['!=', 'book', 'Fund 07']);
        }

        if (!Yii::$app->user->can('super-user')) {

            $query->andWhere("responsibility_center = :responsibility_center", ['responsibility_center' => Yii::$app->user->identity->division]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'entry_id' => $this->entry_id,
        ]);


        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'date_issued', $this->date_issued])
            ->andFilterWhere(['like', 'valid_until', $this->valid_until])
            ->andFilterWhere(['like', 'particulars', $this->particulars])
            ->andFilterWhere(['like', 'document_recieve', $this->document_recieve])
            ->andFilterWhere(['like', 'fund_cluster_code', $this->fund_cluster_code])
            ->andFilterWhere(['like', 'financing_source_code', $this->financing_source_code])
            ->andFilterWhere(['like', 'fund_classification', $this->fund_classification])
            ->andFilterWhere(['like', 'authorization_code', $this->authorization_code])
            ->andFilterWhere(['like', 'mfo_code', $this->mfo_code])
            ->andFilterWhere(['like', 'mfo_name', $this->mfo_name])
            ->andFilterWhere(['like', 'responsibility_center', $this->responsibility_center])
            ->andFilterWhere(['like', 'fund_source', $this->fund_source])
            ->andFilterWhere(['like', 'uacs', $this->uacs])
            ->andFilterWhere(['like', 'general_ledger', $this->general_ledger])
            ->andFilterWhere(['like', 'allotment_class', $this->allotment_class])
            ->andFilterWhere(['like', 'nca_nta', $this->nca_nta])
            ->andFilterWhere(['like', 'total_ors', $this->total_ors])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'allotment_type', $this->allotment_type])
            ->andFilterWhere(['like', 'book', $this->book])
            ->andFilterWhere(['like', 'carp_101', $this->carp_101]);

        return $dataProvider;
    }
}
