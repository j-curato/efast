<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TripTicket;

/**
 * TripTicketSearch represents the model behind the search form of `app\models\TripTicket`.
 */
class TripTicketSearch extends TripTicket
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',  'authorized_by'], 'integer'],
            [['date', 'serial_no', 'driver', 'purpose', 'created_at', 'car_id'], 'safe'],
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
        $query = TripTicket::find();

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
        $query->joinWith('carDriver');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,

            'authorized_by' => $this->authorized_by,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'car_id', $this->car_id])
            ->andFilterWhere([
                'or',
                ['like', 'employee.f_name', $this->driver],
                ['like', 'employee.m_name', $this->driver],
                ['like', 'employee.l_name', $this->driver]

            ]);


        return $dataProvider;
    }
}
