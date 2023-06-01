<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Acics;

/**
 * AcicsSearch represents the model behind the search form of `app\models\Acics`.
 */
class AcicsSearch extends Acics
{
    public $notInBank = false;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['serial_number', 'date_issued', 'created_at', 'fk_book_id'], 'safe'],
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
        $query = Acics::find();

        // add conditions that should always apply here
        if ($this->notInBank === true) {
            $query->andWhere("NOT EXISTS (SELECT 
            acic_in_bank_items.fk_acic_id
            FROM acic_in_bank_items 
            WHERE 
            acic_in_bank_items.is_deleted   = 0
            AND acic_in_bank_items.fk_acic_id = acics.id
            )");
        }
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
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_issued' => $this->date_issued,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'books.name', $this->fk_book_id]);

        return $dataProvider;
    }
}
