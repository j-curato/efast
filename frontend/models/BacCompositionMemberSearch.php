<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BacCompositionMember;

/**
 * BacCompositionMemberSearch represents the model behind the search form of `app\models\BacCompositionMember`.
 */
class BacCompositionMemberSearch extends BacCompositionMember
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bac_composition_id', 'bac_position_id'], 'integer'],
            [['employee_id'], 'safe'],
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
        $query = BacCompositionMember::find();

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
            'bac_composition_id' => $this->bac_composition_id,
            'bac_position_id' => $this->bac_position_id,
        ]);

        $query->andFilterWhere(['like', 'employee_id', $this->employee_id]);

        return $dataProvider;
    }
}
