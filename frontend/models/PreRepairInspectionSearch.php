<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PreRepairInspection;

/**
 * PreRepairInspectionSearch represents the model behind the search form of `app\models\PreRepairInspection`.
 */
class PreRepairInspectionSearch extends PreRepairInspection
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',], 'integer'],
            [['serial_number', 'date', 'findings', 'recommendation', 'created_at', 'fk_requested_by', 'fk_accountable_person'], 'safe'],
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
        $query = PreRepairInspection::find();

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
        $query->joinWith('requestedBy');
        $query->joinWith('accountablePerson');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,

            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'findings', $this->findings])
            ->andFilterWhere([
                'or',
                ['like', 'employee.f_name', $this->fk_accountable_person],
                ['like', 'employee.m_name', $this->fk_accountable_person],
                ['like', 'employee.l_name', $this->fk_accountable_person]
            ])
            ->andFilterWhere([
                'or',
                ['like', 'employee.f_name', $this->fk_requested_by],
                ['like', 'employee.m_name', $this->fk_requested_by],
                ['like', 'employee.l_name', $this->fk_requested_by]
            ])
            ->andFilterWhere(['like', 'recommendation', $this->recommendation]);

        return $dataProvider;
    }
}
