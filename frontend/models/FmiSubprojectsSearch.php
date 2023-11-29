<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FmiSubprojects;

/**
 * FmiSubprojectsSearch represents the model behind the search form of `app\models\FmiSubprojects`.
 */
class FmiSubprojectsSearch extends FmiSubprojects
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_province_id', 'fk_municipality_id', 'fk_barangay_id', 'fk_fmi_batch_id', 'project_duration', 'project_road_length'], 'integer'],
            [['purok', 'project_start_date', 'bank_account_name', 'bank_account_number', 'created_at'], 'safe'],
            [['grant_amount', 'equity_amount'], 'number'],
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
        $query = FmiSubprojects::find();

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
            'fk_province_id' => $this->fk_province_id,
            'fk_municipality_id' => $this->fk_municipality_id,
            'fk_barangay_id' => $this->fk_barangay_id,
            'fk_fmi_batch_id' => $this->fk_fmi_batch_id,
            'project_duration' => $this->project_duration,
            'project_road_length' => $this->project_road_length,
            'project_start_date' => $this->project_start_date,
            'grant_amount' => $this->grant_amount,
            'equity_amount' => $this->equity_amount,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'purok', $this->purok])
            ->andFilterWhere(['like', 'bank_account_name', $this->bank_account_name])
            ->andFilterWhere(['like', 'bank_account_number', $this->bank_account_number]);

        return $dataProvider;
    }
}
