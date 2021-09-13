<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rod;
use Yii;

/**
 * RodSearch represents the model behind the search form of `app\models\Rod`.
 */
class RodSearch extends Rod
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rod_number', 'province'], 'safe'],
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
        $province = Yii::$app->user->identity->province;
        $q = Rod::find();
        if (
            $province === 'adn' ||
            $province === 'ads' ||
            $province === 'sds' ||
            $province === 'sdn' ||
            $province === 'pdi'
        ) {
            $q->where('province = :province', ['province' => $province]);
        }
        $query = $q;

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
        $query->andFilterWhere(['like', 'rod_number', $this->rod_number])
            ->andFilterWhere(['like', 'province', $this->province]);

        return $dataProvider;
    }
}
