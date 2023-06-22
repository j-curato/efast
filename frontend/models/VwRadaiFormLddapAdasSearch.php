<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwRadaiFormLddapAdas;

/**
 * VwRadaiFormLddapAdasSearch represents the model behind the search form of `app\models\VwRadaiFormLddapAdas`.
 */
class VwRadaiFormLddapAdasSearch extends VwRadaiFormLddapAdas
{
    public $bookFilter;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],

            [[
                'lddap_no',
                'mode_of_payment_name',
                'acic_no',
                'issuance_date',
                'check_or_ada_no',
                'book_name',
                'bookFilter'
            ], 'safe'],

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
        $query = VwRadaiFormLddapAdas::find();

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

        $query->andFilterWhere(['like', 'lddap_no', $this->lddap_no])
            ->andFilterWhere(['like', 'mode_of_payment_name', $this->mode_of_payment_name])
            ->andFilterWhere(['like', 'acic_no', $this->acic_no])
            ->andFilterWhere(['like', 'issuance_date', $this->issuance_date])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'book_name', $this->bookFilter])
            ->andFilterWhere(['like', 'check_or_ada_no', $this->check_or_ada_no]);

        return $dataProvider;
    }
}
