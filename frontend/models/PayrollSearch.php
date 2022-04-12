<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Payroll;

/**
 * PayrollSearch represents the model behind the search form of `app\models\Payroll`.
 */
class PayrollSearch extends Payroll
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'process_ors_id'], 'integer'],
            [['payroll_number', 'reporting_period', 'type', 'created_at'], 'safe'],
            [['amount'], 'number'],
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
        $query = Payroll::find();

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
            'process_ors_id' => $this->process_ors_id,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'payroll_number', $this->payroll_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
