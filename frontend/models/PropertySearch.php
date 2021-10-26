<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Property;

/**
 * PropertySearch represents the model behind the search form of `app\models\Property`.
 */
class PropertySearch extends Property
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_number', 'iar_number', 'article', 'model', 'serial_number'], 'safe'],
            [['book_id', 'unit_of_measure_id', 'employee_id', 'quantity'], 'integer'],
            [['acquisition_amount'], 'number'],
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
        $query = Property::find();

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
            'book_id' => $this->book_id,
            'unit_of_measure_id' => $this->unit_of_measure_id,
            'employee_id' => $this->employee_id,
            'quantity' => $this->quantity,
            'acquisition_amount' => $this->acquisition_amount,
        ]);

        $query->andFilterWhere(['like', 'property_number', $this->property_number])
            ->andFilterWhere(['like', 'iar_number', $this->iar_number])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number]);

        return $dataProvider;
    }
}
