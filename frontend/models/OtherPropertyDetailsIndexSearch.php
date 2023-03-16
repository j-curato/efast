<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OtherPropertyDetailsIndex;
use Yii;

/**
 * OtherPropertyDetailsIndexSearch represents the model behind the search form of `app\models\OtherPropertyDetailsIndex`.
 */
class OtherPropertyDetailsIndexSearch extends OtherPropertyDetailsIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'salvage_value_prcnt', 'useful_life'], 'integer'],
            [['description', 'article'], 'string'],
            [['office_name', 'property_number', 'general_ledger'], 'string', 'max' => 255],
            [['uacs'], 'string', 'max' => 30],
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
        $query = OtherPropertyDetailsIndex::find();

        // add conditions that should always apply here

        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('office_name = :office_name', ['office_name' => $user_data->office->office_name]);
        }
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
            'salvage_value_prcnt' => $this->salvage_value_prcnt,
            'useful_life' => $this->useful_life,

        ]);
        $query
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'property_number', $this->property_number])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'uacs', $this->uacs])
            ->andFilterWhere(['like', 'general_ledger', $this->general_ledger])
            ->andFilterWhere(['like', 'article', $this->article]);

        return $dataProvider;
    }
}
