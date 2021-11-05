<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ptr;

/**
 * PtrSearch represents the model behind the search form of `app\models\Ptr`.
 */
class PtrSearch extends Ptr
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ptr_number', 'par_number', 'date', 'reason', 'employee_from', 'employee_to'], 'safe'],
            [['transfer_type_id'], 'integer'],
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
        $query = Ptr::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want employee_to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'transfer_type_id' => $this->transfer_type_id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'ptr_number', $this->ptr_number])
            ->andFilterWhere(['like', 'par_number', $this->par_number])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'employee_from', $this->employee_from])
            ->andFilterWhere(['like', 'employee_to', $this->employee_to]);

        return $dataProvider;
    }
}
