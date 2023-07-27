<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SupplementalPpmp;
use Yii;

/**
 * SupplementalPpmpSearch represents the model behind the search form of `app\models\SupplementalPpmp`.
 */
class SupplementalPpmpSearch extends SupplementalPpmp
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_prepared_by', 'fk_reviewed_by', 'fk_approved_by', 'fk_certified_funds_available_by'], 'integer'],
            [[
                'serial_number',
                'budget_year',
                'cse_type',
                'created_at',

                'fk_office_id',
                'fk_division_id',
                'fk_division_program_unit_id',
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
        $query = SupplementalPpmp::find();

        // add conditions that should always apply here
        $query->joinWith('office');
        $query->joinWith('divisionName');
        $query->joinWith('divisionProgramUnit');
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('office.id = :office_id', ['office_id' => $user_data->office->id]);
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
            'fk_prepared_by' => $this->fk_prepared_by,
            'fk_reviewed_by' => $this->fk_reviewed_by,
            'fk_approved_by' => $this->fk_approved_by,
            'fk_certified_funds_available_by' => $this->fk_certified_funds_available_by,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'office.office_name', $this->fk_office_id])
            ->andFilterWhere(['like', 'divisions.division', $this->fk_division_id])
            ->andFilterWhere(['like', 'division_program_unit.name', $this->fk_division_program_unit_id])
            ->andFilterWhere(['like', 'budget_year', $this->budget_year])
            ->andFilterWhere(['like', 'cse_type', $this->cse_type]);

        return $dataProvider;
    }
}
