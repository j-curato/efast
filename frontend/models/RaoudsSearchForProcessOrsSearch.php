<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Raouds;

/**
 * RaoudsSearchForProcessOrsSearch represents the model behind the search form of `app\models\Raouds`.
 */
class RaoudsSearchForProcessOrsSearch extends Raouds
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'process_ors_id', 'record_allotment_entries_id', 'isActive', 'is_parent', 'process_burs_id'], 'integer'],
            [['serial_number', 'reporting_period'], 'safe'],
            [['obligated_amount', 'burs_amount'], 'number'],
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
        $query = Raouds::find()->where("process_ors_id IS NOT NULL")
            ->andWhere('obligated_amount > 0');

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
            'process_ors_id' => $this->process_ors_id,
            'record_allotment_entries_id' => $this->record_allotment_entries_id,
            'obligated_amount' => $this->obligated_amount,
            'isActive' => $this->isActive,
            'is_parent' => $this->is_parent,
            'process_burs_id' => $this->process_burs_id,
            'burs_amount' => $this->burs_amount,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period]);

        return $dataProvider;
    }
}
