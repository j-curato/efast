<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwTransmittalFormDvs;

/**
 * VwTransmittalFormDvsSearch represents the model behind the search form of `app\models\VwTransmittalFormDvs`.
 */
class VwTransmittalFormDvsSearch extends VwTransmittalFormDvs
{
    public $bookFilter;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_cancelled'], 'integer'],
            [['amtDisbursed', 'taxWitheld'], 'number'],
            [[
                'particular',
                'check_or_ada_no',
                'ada_number',
                'reporting_period',
                'payee',
                'dv_number'
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
        $query = VwTransmittalFormDvs::find();

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
            'is_cancelled' => $this->is_cancelled,
            'amtDisbursed' => $this->amtDisbursed,
            'taxWitheld' => $this->taxWitheld
        ]);

        $query->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'check_or_ada_no', $this->check_or_ada_no])
            ->andFilterWhere(['like', 'ada_number', $this->ada_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number]);

        return $dataProvider;
    }
}
