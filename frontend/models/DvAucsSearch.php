<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DvAucs;

/**
 * DvAucsSearch represents the model behind the search form of `app\models\DvAucs`.
 */
class DvAucsSearch extends DvAucs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['dv_number', 'reporting_period', 'tax_withheld', 'other_trust_liability_withheld','particular'], 'safe'],
            [['net_amount_paid'], 'number'],
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
        $query = DvAucs::find();

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
            'net_amount_paid' => $this->net_amount_paid,
            'is_cancelled' => $this->is_cancelled,
        ]);

        $query->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'tax_withheld', $this->tax_withheld])
            ->andFilterWhere(['like', 'other_trust_liability_withheld', $this->other_trust_liability_withheld]);

        return $dataProvider;
    }
}
