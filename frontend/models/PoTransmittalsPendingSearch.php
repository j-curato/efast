<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PoTransmittalsPending;

/**
 * PoTransmittalsPendingSearch represents the model behind the search form of `app\models\PoTransmittalsPending`.
 */
class PoTransmittalsPendingSearch extends PoTransmittalsPending
{
    /**
     * {@inheritdoc}
     */
    public function rules()

    {
        return [
            [[
                'transmittal_number',
                'date',
                'created_at',
                'status',
                'edited',


            ], 'safe'],
            [[
                'total_withdrawals',


            ], 'number'],
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
        $query = PoTransmittalsPending::find();

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
            'date' => $this->date,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'transmittal_number', $this->transmittal_number])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'edited', $this->edited])
            ->andFilterWhere(['like', 'total_withdrawals', $this->total_withdrawals]);

        return $dataProvider;
    }
}
