<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\Property;
use yii\data\ActiveDataProvider;

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
            [[
                'book_id', 'unit_of_measure_id', 'employee_id', 'property_number', 'iar_number', 'article', 'model', 'serial_number', 'description', 'province',
                'ppe_type',
                'fk_office_id'
            ], 'safe'],
            [['quantity'], 'integer'],
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
        $query->joinWith('office');
        if (!Yii::$app->user->can('ro_property_admin')) {
            $user_data = User::getUserDetails();
            $query->andWhere('office.office_name = :office_name', ['office_name' => $user_data->employee->office->office_name]);
        }
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
        $query->joinWith('book');
        $query->joinWith('employee');
        $query->joinWith('unitOfMeasure');



        // grid filtering conditions
        $query->andFilterWhere([

            'quantity' => $this->quantity,
            'acquisition_amount' => $this->acquisition_amount,
        ]);

        $query->andFilterWhere(['like', 'property.property_number', $this->property_number])
            ->andFilterWhere(['like', 'property.iar_number', $this->iar_number])
            ->andFilterWhere(['like', 'property.article', $this->article])
            ->andFilterWhere(['like', 'property.model', $this->model])
            ->andFilterWhere(['like', 'property.serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'books.name', $this->book_id])
            ->andFilterWhere(['like', 'unit_of_emasure.unit_of_measure', $this->unit_of_measure_id])
            ->andFilterWhere(['like', 'property.description', $this->description])
            ->andFilterWhere(['like', 'property.province', $this->province])
            ->andFilterWhere(['like', 'ppe_type', $this->ppe_type])
            ->andFilterWhere(['like', 'office.office_name', $this->fk_office_id])
            ->andFilterWhere(['or', ['like', 'employee.f_name', $this->employee_id], ['like', 'employee.l_name', $this->employee_id]]);

        return $dataProvider;
    }
}
