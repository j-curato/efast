<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ItHelpdeskCsf;

/**
 * ItHelpdeskCsfSearch represents the model behind the search form of `app\models\ItHelpdeskCsf`.
 */
class ItHelpdeskCsfSearch extends ItHelpdeskCsf
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_it_maintenance_request', 'clarity', 'skills', 'professionalism', 'courtesy', 'response_time'], 'integer'],
            [['serial_number', 'date', 'comment', 'vd_reason', 'created_at'], 'safe'],
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
        $query = ItHelpdeskCsf::find();

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
            'fk_it_maintenance_request' => $this->fk_it_maintenance_request,
            'date' => $this->date,
            'clarity' => $this->clarity,
            'skills' => $this->skills,
            'professionalism' => $this->professionalism,
            'courtesy' => $this->courtesy,
            'response_time' => $this->response_time,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'vd_reason', $this->vd_reason]);

        return $dataProvider;
    }
}
