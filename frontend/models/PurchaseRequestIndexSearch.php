<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseRequestIndex;
use Yii;

/**
 * PurchaseRequestIndexSearch represents the model behind the search form of `app\models\PurchaseRequestIndex`.
 */
class PurchaseRequestIndexSearch extends PurchaseRequestIndex
{
    public $bookFilter = '';
    public $module = '';

    // ...
    // other attributes


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'pr_number',
                'office_name',
                'division',
                'division_program_unit',

                'requested_by',
                'approved_by',
                'book_name',
                'purpose',
                'date',
                'bookFilter',
                'is_cancelled',
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
        $query = PurchaseRequestIndex::find();

        // add conditions that should always apply here

        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('office_name = :office_name', ['office_name' => $user_data->office->office_name]);
            if (!Yii::$app->user->can('po_procurement_admin') || !YIi::$app->user->can('ro_procurement_admin')) {
                $query->andWhere('division = :division', ['division' => $user_data->divisionName->division]);
            }
            $query->andWhere('is_cancelled = "Good"');
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,

        ]);

        $query
            ->andFilterWhere(['like', 'pr_number', $this->pr_number])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'division_program_unit', $this->division_program_unit])
            ->andFilterWhere(['like', 'requested_by', $this->requested_by])
            ->andFilterWhere(['like', 'approved_by', $this->approved_by])
            ->andFilterWhere(['like', 'book_name', $this->book_name])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'is_cancelled', $this->is_cancelled])
            ->andFilterWhere(['like', 'date', $this->date]);

        if (!empty($this->module === 'transaction')) {
            // $query->andWhere(['=', 'book_name', $this->bookFilter]);
            $query->andWhere("forTransactionBal >0");
        }
        // echo $query->createCommand()->getRawSql();
        // die();
        return $dataProvider;
    }
}
