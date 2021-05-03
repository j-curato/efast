<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Raouds;

/**
 * ProcessOrsRaoudsSearch represents the model behind the search form of `app\models\Raouds`.
 */
class ProcessOrsRaoudsSearch extends Raouds
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',  'record_allotment_entries_id', 'isActive', 'is_parent', 'process_burs_id', 'mandatory_reserve_id'], 'integer'],
            [['serial_number','process_ors_id', 'reporting_period'], 'safe'],
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
        $query = Raouds::find()->where("process_ors_id IS NOT NULL");
      

        // add conditions that should always apply here
        $query->joinWith("processOrs");
        // $query->orderBy("process_ors.serial_number DESC")
        // ;

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
            'raouds.id' => $this->id,
            // 'process_ors_id' => $this->process_ors_id,
            'record_allotment_entries_id' => $this->record_allotment_entries_id,
            'obligated_amount' => $this->obligated_amount,
            'isActive' => $this->isActive,
            'is_parent' => $this->is_parent,
            'process_burs_id' => $this->process_burs_id,
            'burs_amount' => $this->burs_amount,
            'mandatory_reserve_id' => $this->mandatory_reserve_id,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'raouds.reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'process_ors.serial_number', $this->process_ors_id]);

        return $dataProvider;
    }
}
