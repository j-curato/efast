<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Saob;

/**
 * SaobSearch represents the model behind the search form of `app\models\Saob`.
 */
class SaobSearch extends Saob
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mfo_pap_code_id', 'document_recieve_id', 'book_id','from_reporting_period', 'to_reporting_period', 'created_at'], 'safe'],
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
        $query = Saob::find();

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
        $query->joinWith('book');
        $query->joinWith('mfo');
        $query->joinWith('documentRecieve');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,

            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'from_reporting_period', $this->from_reporting_period])
            ->andFilterWhere(['like', 'to_reporting_period', $this->to_reporting_period])
            ->andFilterWhere(['like', 'mfo_pap_code.code', $this->mfo_pap_code_id])
            ->andFilterWhere(['like', 'document_recieve.name', $this->document_recieve_id])
            ->andFilterWhere(['like', 'books.name', $this->book_id]);

        return $dataProvider;
    }
}
