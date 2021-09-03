<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cibr;
use Yii;

/**
 * CibrSearch represents the model behind the search form of `app\models\Cibr`.
 */
class CibrSearch extends Cibr
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reporting_period', 'province', 'book_name','is_final'], 'safe'],
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
        $q = Cibr::find();
        if (
            $province === 'adn' ||
            $province === 'ads' ||
            $province === 'sds' ||
            $province === 'sdn' ||
            $province === 'pdi'
        ) {
            $q->where('province LIKE :province', ['province' => $province]);
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
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'is_final', $this->is_final])
            ->andFilterWhere(['like', 'book_name', $this->book_name]);

        return $dataProvider;
    }
}
