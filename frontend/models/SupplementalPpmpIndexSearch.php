<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SupplementalPpmpIndex;
use Yii;

/**
 * SupplementalPpmpIndexSearch represents the model behind the search form of `app\models\SupplementalPpmpIndex`.
 */
class SupplementalPpmpIndexSearch extends SupplementalPpmpIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ttl_qty', 'bal_qty',], 'integer'],
            [[


                'bal_amt',
                'gross_amt',
            ], 'number'],
            [[
                'budget_year',
                'cse_type',
                'serial_number',
                'office_name',
                'division',
                'division_program_unit_name',
                'stock_activity',
                'prepared_by',
                'reviewed_by',
                'approved_by',
                'certified_avail',

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
        $query = SupplementalPpmpIndex::find();

        // add conditions that should always apply here

        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('office_name = :office_name', ['office_name' => $user_data->office->office_name]);
            if (!Yii::$app->user->can('po_procurement_admin') && !YIi::$app->user->can('ro_procurement_admin')) {
                $query->andWhere('divisions.id = :division_id', ['division_id' => $user_data->divisionName->id]);
            }
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
            'ttl_qty' => $this->ttl_qty,
            'bal_qty' => $this->bal_qty,
            'bal_amt' => $this->bal_amt,
            'gross_amt' => $this->gross_amt,


        ]);


        $query
            ->andFilterWhere(['like', 'budget_year', $this->budget_year])
            ->andFilterWhere(['like', 'cse_type', $this->cse_type])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'division_program_unit_name', $this->division_program_unit_name])
            ->andFilterWhere(['like', 'stock_activity', $this->stock_activity])
            ->andFilterWhere(['like', 'prepared_by', $this->prepared_by])
            ->andFilterWhere(['like', 'reviewed_by', $this->reviewed_by])
            ->andFilterWhere(['like', 'certified_avail', $this->certified_avail]);

        return $dataProvider;
    }
}
