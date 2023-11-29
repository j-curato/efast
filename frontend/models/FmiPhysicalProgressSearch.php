<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FmiPhysicalProgress;

/**
 * FmiPhysicalProgressSearch represents the model behind the search form of `app\models\FmiPhysicalProgress`.
 */
class FmiPhysicalProgressSearch extends FmiPhysicalProgress
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_fmi_subproject_id', 'physical_target', 'physical_accomplished'], 'integer'],
            [['serial_number', 'date', 'created_at'], 'safe'],
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
        $query = FmiPhysicalProgress::find();

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
            'fk_fmi_subproject_id' => $this->fk_fmi_subproject_id,
            'date' => $this->date,
            'physical_target' => $this->physical_target,
            'physical_accomplished' => $this->physical_accomplished,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number]);

        return $dataProvider;
    }
}
