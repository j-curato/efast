<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Par;

/**
 * ParSearch represents the model behind the search form of `app\models\Par`.
 */
class ParSearch extends Par
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['par_number', 'property_number', 'date','employee_id','fk_property_id'], 'safe'],
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
        $query = Par::find();

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
        $query->joinWith('property');
        // grid filtering conditions
        $query->andFilterWhere([
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'par_number', $this->par_number])
            ->andFilterWhere(['like', 'property_number', $this->property_number])
            ->andFilterWhere(['or', ['like', 'employee.f_name', $this->employee_id], ['like', 'employee.l_name', $this->employee_id]])
            ->andFilterWhere(['or', ['like', 'property.property_number', $this->fk_property_id], ['like', 'property.article', $this->fk_property_id]])
            ;

        return $dataProvider;
    }
}
