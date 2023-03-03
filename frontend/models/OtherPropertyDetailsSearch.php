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
            [['id'], 'integer'],
            [['fk_property_id', 'created_at', 'fk_chart_of_account_id'], 'safe'],
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
        $query->joinWith('property');
        $query->joinWith('chartOfAccount');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);
        $query->andFilterWhere(['like', 'property.property_number', $this->fk_property_id])
            ->andFilterWhere([
                'or', ['like', 'chart_of_accounts.uacs', $this->fk_chart_of_account_id],
                ['like', 'chart_of_accounts.general_ledger', $this->fk_chart_of_account_id]
            ]);

        return $dataProvider;
    }
}
