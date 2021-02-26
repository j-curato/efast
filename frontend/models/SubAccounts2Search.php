<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubAccounts2;

/**
 * SubAccounts2Search represents the model behind the search form of `app\models\SubAccounts2`.
 */
class SubAccounts2Search extends SubAccounts2
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sub_accounts1_id'], 'integer'],
            [['object_code', 'name'], 'safe'],
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
        $query = SubAccounts2::find();

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
            'sub_accounts1_id' => $this->sub_accounts1_id,
        ]);

        $query->andFilterWhere(['like', 'object_code', $this->object_code])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
