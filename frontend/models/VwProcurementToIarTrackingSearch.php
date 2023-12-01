<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VwProcurementToIarTracking;
use common\models\User;
use Yii;

/**
 * VwProcurementToIarTrackingSearch represents the model behind the search form of `app\models\VwProcurementToIarTracking`.
 */
class VwProcurementToIarTrackingSearch extends VwProcurementToIarTracking
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [[
                'office_name',
                'division',
                'pr_number',
                'rfq_number',
                'aoq_number',
                'payee_name',
                'po_number',
                'rfi_number',
                'ir_number',
                'iar_number',
                'purpose',
                'stock_name',
                'specification'
            ], 'string'],
            [['quantity', 'inspected_quantity'], 'integer'],
            [['unit_cost', 'bidAmount', 'bidGrossAmount'], 'number'],
            [['rfq_date', 'rfq_deadline', 'rfi_date', 'pr_date', 'inspection_from', 'inspection_to'], 'safe'],
            [['pr_is_cancelled', 'rfq_is_cancelled', 'aoq_is_cancelled', 'po_is_cancelled'], 'string', 'max' => 9],

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
        $query = VwProcurementToIarTracking::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $user_data = User::getUserDetails();
        if (!Yii::$app->user->can('ro_procurement_admin')) {
            $query->andWhere('office_name = :office', ['office' => $user_data->employee->office->office_name]);
            if (!Yii::$app->user->can('po_procurement_admin')) {
                $query->andWhere('division = :division', ['division' => $user_data->employee->empDivision->division]);
            }
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'quantity' => $this->quantity,
            'inspected_quantity' => $this->inspected_quantity,
            'unit_cost' => $this->unit_cost,
            'bidAmount' => $this->bidAmount,
            'bidGrossAmount' => $this->bidGrossAmount

        ]);

        $query->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'pr_number', $this->pr_number])
            ->andFilterWhere(['like', 'rfq_number', $this->rfq_number])
            ->andFilterWhere(['like', 'aoq_number', $this->aoq_number])
            ->andFilterWhere(['like', 'payee_name', $this->payee_name])
            ->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'rfi_number', $this->rfi_number])
            ->andFilterWhere(['like', 'ir_number', $this->ir_number])
            ->andFilterWhere(['like', 'iar_number', $this->iar_number])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'stock_name', $this->stock_name])
            ->andFilterWhere(['like', 'specification', $this->specification])
            ->andFilterWhere(['like', 'rfq_date', $this->rfq_date])
            ->andFilterWhere(['like', 'rfq_deadline', $this->rfq_deadline])
            ->andFilterWhere(['like', 'rfi_date', $this->rfi_date])
            ->andFilterWhere(['like', 'pr_date', $this->pr_date])
            ->andFilterWhere(['like', 'inspection_from', $this->inspection_from])
            ->andFilterWhere(['like', 'inspection_to', $this->inspection_to])
            ->andFilterWhere(['like', 'pr_is_cancelled', $this->pr_is_cancelled])
            ->andFilterWhere(['like', 'rfq_is_cancelled', $this->rfq_is_cancelled])
            ->andFilterWhere(['like', 'aoq_is_cancelled', $this->aoq_is_cancelled])
            ->andFilterWhere(['like', 'po_is_cancelled', $this->po_is_cancelled]);

        return $dataProvider;
    }
}
