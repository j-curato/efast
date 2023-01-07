<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrAllotmentView;
use Yii;

/**
 * PrAllotmentViewSearch represents the model behind the search form of `app\models\PrAllotmentView`.
 */
class PrAllotmentViewSearch extends PrAllotmentView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['allotment_entry_id', 'budget_year'], 'integer'],
            [
                [
                    'office_name',
                    'division',
                    'mfo_name',
                    'fund_source_name',
                    'account_title',
                    'amount',
                    'balance',

                ], 'safe'
            ],
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
        $query = PrAllotmentView::find();
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('office_name = :office_name', ['office_name' => $user_data->office->office_name]);
            $query->andWhere('division = :division', ['division' => $user_data->divisionName->division]);
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

        // grid filtering conditions
        $query->andFilterWhere([
            'allotment_entry_id' => $this->allotment_entry_id,
            'budget_year' => $this->budget_year,
        ]);

        $query->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'mfo_name', $this->mfo_name])
            ->andFilterWhere(['like', 'fund_source_name', $this->fund_source_name])
            ->andFilterWhere(['like', 'account_title', $this->account_title])
            ->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'balance', $this->balance]);

        return $dataProvider;
    }
}
