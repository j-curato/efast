<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProcessOrsView;

/**
 * ProcessOrsViewSearch represents the model behind the search form of `app\models\ProcessOrsView`.
 */
class ProcessOrsViewSearch extends ProcessOrsView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'reporting_period',
                'serial_number',
                'tracking_number',
                'particular',
                'account_name',
                'allotment_general_ledger',
                'ors_general_ledger',
                'allotment_uacs',
                'ors_uacs',
            ], 'safe'],

            [['id'], 'integer'],
            [['amount'], 'number'],
            [['serial_number', 'reporting_period', 'tracking_number', 'particular', 'account_name', 'allotment_general_ledger',
             'ors_general_ledger'], 'string', 'max' => 255],
            [['allotment_uacs', 'ors_uacs'], 'string', 'max' => 30],
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
        $query = ProcessOrsView::find();

   


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
            'amount' => $this->amount,
    
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'tracking_number', $this->tracking_number])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'account_name', $this->account_name])
            ->andFilterWhere(['like', 'allotment_uacs', $this->allotment_uacs])
            ->andFilterWhere(['like', 'allotment_general_ledger', $this->allotment_general_ledger])
            ->andFilterWhere(['like', 'ors_uacs', $this->ors_uacs])
            ->andFilterWhere(['like', 'ors_general_ledger', $this->ors_general_ledger])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ;

        return $dataProvider;
    }
}
