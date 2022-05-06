<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Alphalist;
use Yii;

/**
 * AlphalistSearch represents the model behind the search form of `app\models\Alphalist`.
 */
class AlphalistSearch extends Alphalist
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['alphalist_number', 'check_range', 'created_at'], 'safe'],
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
        $query = Alphalist::find();
        if (
            $province === 'adn' ||
            $province === 'ads' ||
            $province === 'sdn' ||
            $province === 'sds' ||
            $province === 'pdi'
        ) {
            $query->where('province =:province', ['province' => $province]);
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
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'alphalist_number', $this->alphalist_number])
            ->andFilterWhere(['like', 'check_range', $this->check_range]);

        return $dataProvider;
    }
}