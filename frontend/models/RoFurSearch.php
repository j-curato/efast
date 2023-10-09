<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RoFur;
use Yii;

/**
 * RoFurSearch represents the model behind the search form of `app\models\RoFur`.
 */
class RoFurSearch extends RoFur
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',], 'integer'],
            [['from_reporting_period', 'to_reporting_period', 'division', 'created_at', 'document_recieve_id'], 'safe'],
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
        $query = RoFur::find();
        $user = Yii::$app->user->can('ro_budget_admin');
        $user_division = Yii::$app->user->identity->division;
        if (!$user) {
            $query->andWere('division = :division', ['division' => $user_division]);
        }
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
        $query->joinWith('documentReceive');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'from_reporting_period', $this->from_reporting_period])
            ->andFilterWhere(['like', 'to_reporting_period', $this->to_reporting_period])
            ->andFilterWhere(['like', 'from_reporting_period', $this->from_reporting_period])
            ->andFilterWhere(['like', 'document_recieve.name', $this->document_recieve_id])
            ->andFilterWhere(['like', 'division', $this->division]);

        return $dataProvider;
    }
}
