<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SsfSpNum;
use Yii;

/**
 * SsfSpNumSearch represents the model behind the search form of `app\models\SsfSpNum`.
 */
class SsfSpNumSearch extends SsfSpNum
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'budget_year', 'fk_office_id', 'fk_citymun_id'], 'integer'],
            [['project_name', 'cooperator', 'equipment', 'date', 'fk_ssf_sp_status_id', 'created_at'], 'safe'],
            [['amount'], 'number'],
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
        $query = SsfSpNum::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $office_id = $user_data->office->id;
            $query->where('fk_office_id = :office_id', ['office_id' => $office_id]);
        }
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'budget_year' => $this->budget_year,
            'fk_office_id' => $this->fk_office_id,
            'fk_citymun_id' => $this->fk_citymun_id,
            'amount' => $this->amount,
            'date' => $this->date,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'project_name', $this->project_name])
            ->andFilterWhere(['like', 'cooperator', $this->cooperator])
            ->andFilterWhere(['like', 'equipment', $this->equipment])
            ->andFilterWhere(['like', 'fk_ssf_sp_status_id', $this->fk_ssf_sp_status_id]);

        return $dataProvider;
    }
}
