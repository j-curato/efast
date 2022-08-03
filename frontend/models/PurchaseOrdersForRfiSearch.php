<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseOrdersForRfi;

/**
 * PurchaseOrdersForRfiSearch represents the model behind the search form of `app\models\PurchaseOrdersForRfi`.
 */
class PurchaseOrdersForRfiSearch extends PurchaseOrdersForRfi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['project_name'], 'string'],
            [['po_date'], 'safe'],
            [['po_number', 'payee', 'division', 'unit'], 'string', 'max' => 255],
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
        $query = PurchaseOrdersForRfi::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want employee_to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([]);

        $query->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'project_name', $this->project_name])
            ->andFilterWhere(['like', 'po_date', $this->po_date])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'division', $this->division]);

        return $dataProvider;
    }
}
