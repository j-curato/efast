<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ItMaintenanceRequest;

/**
 * ItMaintenanceRequestSearch represents the model behind the search form of `app\models\ItMaintenanceRequest`.
 */
class ItMaintenanceRequestSearch extends ItMaintenanceRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_requested_by', 'fk_worked_by', 'fk_division_id'], 'integer'],
            [['serial_number', 'date_requested', 'date_accomplished', 'description', 'type', 'created_at'], 'safe'],
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
        $query = ItMaintenanceRequest::find();

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
            'fk_requested_by' => $this->fk_requested_by,
            'fk_worked_by' => $this->fk_worked_by,
            'fk_division_id' => $this->fk_division_id,
            'date_requested' => $this->date_requested,
            'date_accomplished' => $this->date_accomplished,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
