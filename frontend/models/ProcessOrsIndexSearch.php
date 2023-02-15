<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProcessOrsIndex;

/**
 * ProcessOrsIndexSearch represents the model behind the search form of `app\models\ProcessOrsIndex`.
 */
class ProcessOrsIndexSearch extends ProcessOrsIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'serial_number',
                'reporting_period',
                'date',
                'tracking_number',
                'particular',
                'r_center',
                'payee',
            ], 'safe'],
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
    public function search($params, $type = 'ors')
    {
        $query = ProcessOrsIndex::find();
        $query->where('type = :type', ['type' => $type]);
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
            'id' => $this->id
        ]);


        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'tracking_number', $this->tracking_number])
            ->andFilterWhere(['like', 'r_center', $this->r_center]);

        return $dataProvider;
    }
}
