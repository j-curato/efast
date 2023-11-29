<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FmiProjectCompletions;

/**
 * FmiProjectCompletionsSearch represents the model behind the search form of `app\models\FmiProjectCompletions`.
 */
class FmiProjectCompletionsSearch extends FmiProjectCompletions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_office_id', 'fk_fmi_subproject_id'], 'integer'],
            [['serial_number', 'completion_date', 'turnover_date', 'spcr_link', 'certificate_of_project_link', 'certificate_of_turnover_link', 'reporting_period', 'created_at'], 'safe'],
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
        $query = FmiProjectCompletions::find();

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
            'fk_office_id' => $this->fk_office_id,
            'fk_fmi_subproject_id' => $this->fk_fmi_subproject_id,
            'completion_date' => $this->completion_date,
            'turnover_date' => $this->turnover_date,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'spcr_link', $this->spcr_link])
            ->andFilterWhere(['like', 'certificate_of_project_link', $this->certificate_of_project_link])
            ->andFilterWhere(['like', 'certificate_of_turnover_link', $this->certificate_of_turnover_link])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period]);

        return $dataProvider;
    }
}
