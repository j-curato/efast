<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WithholdingAndRemittanceSummary;

/**
 * WithholdingAndRemittanceSummarySearch represents the model behind the search form of `app\models\WithholdingAndRemittanceSummary`.
 */
class WithholdingAndRemittanceSummarySearch extends WithholdingAndRemittanceSummary
{
    /**
     * {@inheritdoc}
     */
    public $_newProperty;


    public function getNewProperty()
    {

        return $this->_newProperty;
    }


    public function setNewProperty($newProperty)
    {

        $this->_newProperty = $newProperty;
    }

    public function rules()
    {
      
        return [
            [['amount',], 'number'],
            [[
                'ors_number',
                'payroll_number',
                'dv_number',
                'object_code',
                'account_title',
                'type',
                'payee',
                'newProperty',
                'payee_id'

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

        $query = WithholdingAndRemittanceSummary::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if ($this->_newProperty === 'remittance_to_payee') {
            $query->where('payee_id  = :payee_id', ['payee_id' => $this->payee_id]);
        }
        if (!$this->validate()) {

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([]);

        $query->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'ors_number', $this->ors_number])
            ->andFilterWhere(['like', 'payroll_number', $this->payroll_number])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'object_code', $this->object_code])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'account_title', $this->account_title]);
        if ($this->_newProperty == '') {
            $dataProvider = new \yii\data\ArrayDataProvider();
        } else if ($this->_newProperty == 'remittance_to_payee' && $this->payee_id === '') {
            $dataProvider = new \yii\data\ArrayDataProvider();
        }

        return $dataProvider;
    }
}
