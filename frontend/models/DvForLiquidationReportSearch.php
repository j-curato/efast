<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DvForLiquidationReport;

/**
 * DvForLiquidationReportSearch represents the model behind the search form of `app\models\DvForLiquidationReport`.
 */
class DvForLiquidationReportSearch extends DvForLiquidationReport
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [


            // [['id', 'dv_aucs_id', 'raoud_id'], 'integer'],
            [[
                'payee',
                'check_number',
                'ada_number',
                'particular',
                'issuance_date',
                'total_disbursed',
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
        $query = DvForLiquidationReport::find()->where('balance!=0');

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
        // $this->id=$dv_id;

        $query
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'check_number', $this->check_number])
            ->andFilterWhere(['like', 'ada_number', $this->ada_number])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'issuance_date', $this->issuance_date])
            ->andFilterWhere(['like', 'total_disbursed', $this->total_disbursed]);
        return $dataProvider;
    }
}
