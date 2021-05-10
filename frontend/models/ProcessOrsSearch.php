<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProcessOrs;

/**
 * ProcessOrsSearch represents the model behind the search form of `app\models\ProcessOrs`.
 */
class ProcessOrsSearch extends ProcessOrs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'transaction_id', 'document_recieve_id', 'mfo_pap_code_id', 'fund_source_id', 'book_id'], 'integer'],
            [['reporting_period', 'serial_number', 'obligation_number', 'funding_code'], 'safe'],
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
        $query = ProcessOrs::find()->where("process_ors.is_cancelled =false");
        // Query Table A
        // $tableA = ProcessOrs::find()->where("process_ors.is_cancelled =false");
        // Query table B
        // $tableB =ProcessBurs::find()->where("process_burs.is_cancelled =false");
        // // Union table A and B
        // $tableA->union($tableB);
        // $query = ProcessOrs::find()->select('*')->from(['random_name' => $tableA]);
        // add conditions that should always apply here
        $query->join('LEFT JOIN', '(SELECT raouds.process_ors_id,SUM(raoud_entries.amount) as total_obligate
        from raouds,raoud_entries
        where raouds.id =raoud_entries.raoud_id
        GROUP BY raouds.process_ors_id
        )as q', 'q.process_ors_id = process_ors.id');
        // $query->join('LEFT JOIN', '(SELECT dv_aucs_entries.process_ors_id,
        // SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,
        // SUM(dv_aucs_entries.amount_disbursed)+ SUM(dv_aucs_entries.vat_nonvat)
        // + SUM(dv_aucs_entries.ewt_goods_services)+ SUM(dv_aucs_entries.compensation)
        // as tt
        // from dv_aucs_entries
        // GROUP BY dv_aucs_entries.process_ors_id
        // )as qwer', 'qwer.process_ors_id = process_ors.id');

        $query->andWhere('q.total_obligate >0');
        // $tableA = ProcessOrs::find()->where("process_ors.id NOT IN (SELECT DISTINCT dv_aucs_entries.process_ors_id 
        // dv_aucs_entries
        // )");
        // $tableA->join('LEFT JOIN', '0 as total_obligate,0as total_obligate
        // from raouds,raoud_entries
        // where raouds.id =raoud_entries.raoud_id
        // GROUP BY raouds.process_ors_id
        // )as q', 'q.process_ors_id = process_ors.id');
        // $query->andWhere('q.total_obligate >qwer.tt');


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
            'transaction_id' => $this->transaction_id,
            'document_recieve_id' => $this->document_recieve_id,
            'mfo_pap_code_id' => $this->mfo_pap_code_id,
            'fund_source_id' => $this->fund_source_id,
            'book_id' => $this->book_id,
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'process_ors.serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'obligation_number', $this->obligation_number])
            ->andFilterWhere(['like', 'funding_code', $this->funding_code]);

        return $dataProvider;
    }
}
