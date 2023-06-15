<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwDvsInBank;

/**
 * VwDvsInBankSearch represents the model behind the search form of `app\models\VwDvsInBank`.
 */
class VwDvsInBankSearch extends VwDvsInBank
{
    public $type = 'cash_receive';
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['grossAmt'], 'number'],
            [[
                'check_or_ada_no',
                'ada_number',
                'issuance_date',
                'dv_number',
                'payee',
                'orsNums',
                'particular',
                'acic_num',

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
        $query = VwDvsInBank::find();

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
        ]);

        $query->andFilterWhere(['like', 'check_or_ada_no', $this->check_or_ada_no])
            ->andFilterWhere(['like', 'ada_number', $this->ada_number])
            ->andFilterWhere(['like', 'issuance_date', $this->issuance_date])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'orsNums', $this->orsNums])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'acic_num', $this->acic_num]);

        return $dataProvider;
    }
}
