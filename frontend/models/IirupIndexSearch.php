<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\IirupIndex;

/**
 * IirupIndexSearch represents the model behind the search form of `app\models\IirupIndex`.
 */
class IirupIndexSearch extends IirupIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['serial_number', 'office_name', 'approved_by', 'accountable_officer'], 'safe'],
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
        $query = IirupIndex::find();

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
        $query->andFilterWhere(['like', 'office_name', $this->office_name]);
        $query->andFilterWhere(['like', 'approved_by', $this->approved_by]);
        $query->andFilterWhere(['like', 'accountable_officer', $this->accountable_officer]);
        return $dataProvider;
    }
}
