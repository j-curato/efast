<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Liquidation;

/**
 * LiquidataionSearch represents the model behind the search form of `app\models\Liquidation`.
 */
class LiquidataionSearch extends Liquidation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'payee_id', 'responsibility_center_id'], 'integer'],
            [['check_date', 'check_number', 'dv_number', 'particular'], 'safe'],
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
        $query = Liquidation::find();

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
            'responsibility_center_id' => $this->responsibility_center_id,
        ]);

        $query->andFilterWhere(['like', 'check_date', $this->check_date])
            ->andFilterWhere(['like', 'check_number', $this->check_number])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'particular', $this->particular]);

        return $dataProvider;
    }
}
