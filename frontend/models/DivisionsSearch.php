<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Divisions;

/**
 * DivisionsSearch represents the model behind the search form of `app\models\Divisions`.
 */
class DivisionsSearch extends Divisions
{
    public $employee;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['division', 'created_at', 'fk_division_chief'], 'safe'],
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
        $query = Divisions::find();

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
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere([
                'or', ['like', 'employee.l_name', $this->fk_division_chief],
                ['like', 'employee.f_name', $this->fk_division_chief],
                ['like', 'employee.m_name', $this->fk_division_chief]
            ]);

        return $dataProvider;
    }
}
