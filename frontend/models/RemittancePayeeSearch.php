<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RemittancePayee;

/**
 * RemittancePayeeSearch represents the model behind the search form of `app\models\RemittancePayee`.
 */
class RemittancePayeeSearch extends RemittancePayee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'payee_id'], 'integer'],
            [['object_code'], 'safe'],
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
        $query = RemittancePayee::find();

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
            'payee_id' => $this->payee_id,
        ]);

        $query->andFilterWhere(['like', 'object_code', $this->object_code]);

        return $dataProvider;
    }
}
