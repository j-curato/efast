<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RlsddpIndex;

/**
 * RlsddpIndexSearch represents the model behind the search form of `app\models\RlsddpIndex`.
 */
class RlsddpIndexSearch extends RlsddpIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['accountable_officer', 'supervisor', 'circumstances'], 'string'],
            [['id'], 'integer'],
            [['date', 'blotter_date'], 'safe'],
            [['serial_number', 'police_station', 'office_name', 'status'], 'string', 'max' => 255],
            [['blottered'], 'string', 'max' => 3],
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
        $query = RlsddpIndex::find();

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

        $query->andFilterWhere(['like', 'accountable_officer', $this->accountable_officer])
            ->andFilterWhere(['like', 'supervisor', $this->supervisor])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'blotter_date', $this->blotter_date])
            ->andFilterWhere(['like', 'circumstances', $this->circumstances])
            ->andFilterWhere(['like', 'police_station', $this->police_station])
            ->andFilterWhere(['like', 'blottered', $this->blottered])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number]);

        return $dataProvider;
    }
}