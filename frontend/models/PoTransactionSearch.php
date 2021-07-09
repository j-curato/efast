<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PoTransaction;

/**
 * PoTransactionSearch represents the model behind the search form of `app\models\PoTransaction`.
 */
class PoTransactionSearch extends PoTransaction
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'po_responsibility_center_id'], 'integer'],
            [['payee', 'particular', 'payroll_number'], 'safe'],
            [['amount'], 'number'],
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
        $query = PoTransaction::find();

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
            'po_responsibility_center_id' => $this->po_responsibility_center_id,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'payroll_number', $this->payroll_number]);

        return $dataProvider;
    }
}
