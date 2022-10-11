<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OtherPropertyDetails;

/**
 * OtherPropertyDetailsSearch represents the model behind the search form of `app\models\OtherPropertyDetails`.
 */
class OtherPropertyDetailsSearch extends OtherPropertyDetails
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_property_id', 'depreciation_schedule', 'fk_chart_of_account_id'], 'integer'],
            [['created_at'], 'safe'],
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
        $query = OtherPropertyDetails::find();

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
            'fk_property_id' => $this->fk_property_id,
            'depreciation_schedule' => $this->depreciation_schedule,
            'fk_chart_of_account_id' => $this->fk_chart_of_account_id,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
