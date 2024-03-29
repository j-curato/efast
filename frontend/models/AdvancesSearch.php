<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Advances;

/**
 * AdvancesSearch represents the model behind the search form of `app\models\Advances`.
 */
class AdvancesSearch extends Advances
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['province', 'report_type', 'particular','nft_number','bank_account_id'], 'safe'],
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
        $query = Advances::find();

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
            'bank_account_id' => $this->bank_account_id,
        ]);

        $query->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'report_type', $this->report_type])
            ->andFilterWhere(['like', 'nft_number', $this->nft_number])
            ->andFilterWhere(['like', 'particular', $this->particular]);

        return $dataProvider;
    }
}
