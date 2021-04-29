<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transmittal;

/**
 * TransmittalSearch represents the model behind the search form of `app\models\Transmittal`.
 */
class TransmittalSearch extends Transmittal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', ], 'integer'],
            [['transmittal_number', 'location','date'], 'safe'],
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
        $query = Transmittal::find();

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
        ]);

        $query->andFilterWhere(['like', 'transmittal_number', $this->transmittal_number])
            ->andFilterWhere(['like', 'location', $this->location]);

        return $dataProvider;
    }
}
