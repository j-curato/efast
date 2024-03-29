<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rao;
use DateTime;

/**
 * RaoSearch represents the model behind the search form of `app\models\Rao`.
 */
class RaoSearch extends Rao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['ors_amount', 'allotment_amount'], 'number'],
            [[
                'document_name',
                'fund_cluster_code_name',
                'financing_source_code_name',
                'fund_category_and_classification_code_name',
                'authorization_code_name',
                'mfo_pap_code_name',
                'fund_source_name',
                'reporting_period',
                'uacs',
                'general_ledger',
                'book_name',
                'division',
                'allotment_number',
                'ors_number',
                'payee',
                'particular',
                'is_cancelled'
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
    public function search($params, $to_reporting_period)
    {
        $query = Rao::find();

        $from_reporting_period = DateTime::createFromFormat('Y-m', $to_reporting_period)->format('Y') . '-01';

        $query->andWhere('reporting_period >= :from_reporting_period', ['from_reporting_period' => $from_reporting_period]);
        $query->andWhere('reporting_period <= :to_reporting_period', ['to_reporting_period' => $to_reporting_period]);
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



        $query->andFilterWhere(['like', 'document_name', $this->document_name])
            ->andFilterWhere(['like', 'fund_cluster_code_name', $this->fund_cluster_code_name])
            ->andFilterWhere(['like', 'financing_source_code_name', $this->financing_source_code_name])
            ->andFilterWhere(['like', 'fund_category_and_classification_code_name', $this->fund_category_and_classification_code_name])
            ->andFilterWhere(['like', 'authorization_code_name', $this->authorization_code_name])
            ->andFilterWhere(['like', 'mfo_pap_code_name', $this->mfo_pap_code_name])
            ->andFilterWhere(['like', 'fund_source_name', $this->fund_source_name])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'uacs', $this->uacs])
            ->andFilterWhere(['like', 'general_ledger', $this->general_ledger])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'ors_amount', $this->ors_amount])
            ->andFilterWhere(['like', 'allotment_amount', $this->allotment_amount])
            ->andFilterWhere(['like', 'allotment_number', $this->allotment_number])
            ->andFilterWhere(['like', 'ors_number', $this->ors_number])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'is_cancelled', $this->is_cancelled])
            ->andFilterWhere(['like', 'division', $this->division]);

        return $dataProvider;
    }
}
