<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CashDeposits;

/**
 * CashDepositsSearch represents the model behind the search form of `app\models\CashDeposits`.
 */
class CashDepositsSearch extends CashDeposits
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_mgrfr_id'], 'integer'],
            [['serial_number', 'reporting_period', 'date', 'particular', 'created_at'], 'safe'],
            [['matching_grant_amount', 'equity_amount', 'other_amount'], 'number'],
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
        $query = CashDeposits::find();

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
            'fk_mgrfr_id' => $this->fk_mgrfr_id,
            'date' => $this->date,
            'matching_grant_amount' => $this->matching_grant_amount,
            'equity_amount' => $this->equity_amount,
            'other_amount' => $this->other_amount,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'particular', $this->particular]);

        return $dataProvider;
    }
}
