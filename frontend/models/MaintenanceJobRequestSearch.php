<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MaintenanceJobRequest;

/**
 * MaintenanceJobRequestSearch represents the model behind the search form of `app\models\MaintenanceJobRequest`.
 */
class MaintenanceJobRequestSearch extends MaintenanceJobRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['date_requested', 'problem_description', 'recommendation', 'action_taken', 'created_at','fk_employee_id','fk_responsibility_center_id'], 'safe'],
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
        $query = MaintenanceJobRequest::find();

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
        $query->joinWith('employee');
        $query->joinWith('responsibilityCenter');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_requested' => $this->date_requested,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'problem_description', $this->problem_description])
            ->andFilterWhere(['like', 'recommendation', $this->recommendation])
            ->andFilterWhere(['like', 'responsibility_center.name', $this->fk_responsibility_center_id])
            ->andFilterWhere(['or',['like', 'employee.f_name', $this->fk_employee_id],['like', 'employee.m_name', $this->fk_employee_id],['like', 'employee.l_name', $this->fk_employee_id]])
            ->andFilterWhere(['like', 'action_taken', $this->action_taken]);

        return $dataProvider;
    }
}
