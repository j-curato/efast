<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RecordAllotmentForOrs;

/**
 * RecordAllotmentForOrsSearch represents the model behind the search form of `app\models\RecordAllotmentForOrs`.
 */
class RecordAllotmentForOrsSearch extends RecordAllotmentForOrs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['amount', 'balance'], 'number'],
            [['serial_number'], 'string', 'max' => 50],
            [['mfo_code', 'mfo_name', 'fund_source_name', 'general_ledger'], 'string', 'max' => 255],
            [['uacs'], 'string', 'max' => 30],

            [['uacs', 'mfo_code', 'mfo_name', 'fund_source_name', 'general_ledger', 'particulars'], 'safe'],
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
    public function search($params, $type)
    {
        $query = RecordAllotmentForOrs::find();

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
        if ($type === 'burs') {
            $query->andFilterWhere(['like', 'book_name', 'Fund 07']);
        } else if ($type === 'ors') {
            $query->andFilterWhere(['!=', 'book_name', 'Fund 07']);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
            'balance' => $this->balance,
        ]);

        $query
            ->andFilterWhere(['like', 'mfo_code', $this->mfo_code])
            ->andFilterWhere(['like', 'mfo_name', $this->mfo_name])
            ->andFilterWhere(['like', 'fund_source_name', $this->fund_source_name])
            ->andFilterWhere(['like', 'uacs', $this->uacs])
            ->andFilterWhere(['like', 'general_ledger', $this->general_ledger])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number]);;
        return $dataProvider;
    }
}
