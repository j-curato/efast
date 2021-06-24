<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ConsoDetailedDvSearch represents the model behind the search form of `app\models\Advances`.
 */
class ConsoDetailedDvSearch extends ConsoDetailedDv
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mfo_code', 'mfo_name', 'mfo_description'], 'safe'],
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
        // $query = ConsoDetailedDv::find();

        // add conditions that should a lways apply here
        $q = Yii::$app->db->createCommand("CALL q('2021-01')");
        $query = ConsoDetailedDv::find($q);
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
        $query->andFilterWhere([]);

        $query->andFilterWhere(['like', 'mfo_code', $this->mfo_code])
            ->andFilterWhere(['like', 'mfo_name', $this->mfo_name])
            ->andFilterWhere(['like', 'mfo_description', $this->mfo_description]);

        return $dataProvider;
    }
}
