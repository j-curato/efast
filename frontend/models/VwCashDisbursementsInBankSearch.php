<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwCashDisbursementsInBank;

/**
 * VwCashDisbursementsInBankSearch represents the model behind the search form of `app\models\VwCashDisbursementsInBank`.
 */
class VwCashDisbursementsInBankSearch extends VwCashDisbursementsInBank
{
    public $type;
    public $bookFilter;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'reporting_period',
                'issuance_date',
                'book_name',
                'mode_name',
                'check_or_ada_no',
                'ada_number',
                'type',
                'bookFilter',
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
        $query = VwCashDisbursementsInBank::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if ($this->type === 'acic') {

            if (empty($this->bookFilter)) {
                $query->where('0=1');
                return $dataProvider;
            } else {
                $query->andFilterWhere(['like', 'book_name', $this->bookFilter]);
            }
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'issuance_date', $this->issuance_date])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'mode_name', $this->mode_name])
            ->andFilterWhere(['like', 'ada_number', $this->ada_number])
            ->andFilterWhere(['like', 'check_or_ada_no', $this->check_or_ada_no]);

        return $dataProvider;
    }
}
