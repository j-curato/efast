<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseOrdersForRfi;
use Yii;

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
            [['po_aoq_item_id'], 'integer'],
            [['project_title'], 'string'],
            [[
                'po_number', 'payee', 'division', 'unit',
                'po_number',
                'project_title',
                'pr_requested_by',
                'purpose',
                'stock_title',
                'specification',
                'unit_of_measure',

            ], 'string', 'max' => 255],
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
        $query->andWhere('quantity >0');
        if (!Yii::$app->user->can('super-user')) {
            $query->andWhere('division = :division', ['division' => Yii::$app->user->identity->division]);
        }
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
            ->andFilterWhere(['like', 'project_title', $this->project_title])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'project_title', $this->project_title])
            ->andFilterWhere(['like', 'pr_requested_by', $this->pr_requested_by])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'stock_title', $this->stock_title])
            ->andFilterWhere(['like', 'specification', $this->specification])
            ->andFilterWhere(['like', 'unit_of_measure', $this->unit_of_measure])
            ->andFilterWhere(['like', 'quantity', $this->quantity])
            ->andFilterWhere(['like', 'unit_cost', $this->unit_cost]);

        return $dataProvider;
    }
}
