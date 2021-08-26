<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CancelledChecksView;
use Yii;

/**
 * CancelledChecksViewSearch represents the model behind the search form of `app\models\CancelledChecksView`.
 */
class CancelledChecksViewSearch extends CancelledChecksView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'province',
                'reporting_period',
                'check_date',
                'check_number',
            ], 'safe'],
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
        $q = CancelledChecksView::find();
        $province = strtolower(Yii::$app->user->identity->province);
        if (
            $province === 'adn' ||
            $province === 'ads' ||
            $province === 'sdn' ||
            $province === 'sds' ||
            $province === 'pdi'
        ) {
            $q->where('province = :province', ['province' => $province]);
        }
        // add conditions that should always apply here
        $query = $q;
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


        $query->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'check_date', $this->check_date])
            ->andFilterWhere(['like', 'check_number', $this->check_number]);

        return $dataProvider;
    }
}
