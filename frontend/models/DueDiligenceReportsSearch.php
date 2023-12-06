<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;
use app\models\DueDiligenceReports;

/**
 * DueDiligenceReportsSearch represents the model behind the search form of `app\models\DueDiligenceReports`.
 */
class DueDiligenceReportsSearch extends DueDiligenceReports
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id', 'supplier_has_business_permit',
                'supplier_is_bir_registered',
                'supplier_has_officer_connection',
                'supplier_is_financial_capable', 'supplier_is_authorized_dealer', 'supplier_has_quality_material', 'supplier_can_comply_specs', 'supplier_has_legal_issues', 'fk_mgrfr_id', 'fk_conducted_by', 'fk_noted_by'
            ], 'integer'],
            [[
                'serial_number',
                'fk_office_id',
                'supplier_is_registered', 'supplier_nursery', 'comments', 'created_at'
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
        $query = DueDiligenceReports::find();
        $query->joinWith('office');
        // add conditions that should always apply here
        if (!Yii::$app->user->can('ro_rapid_fma')) {
            $user_data = User::getUserDetails();
            $query->andWhere(['fk_office_id' => $user_data->employee->office->id]);
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
            'supplier_has_business_permit' => $this->supplier_has_business_permit,
            'supplier_is_bir_registered' => $this->supplier_is_bir_registered,
            'supplier_has_officer_connection' => $this->supplier_has_officer_connection,
            'supplier_is_financial_capable' => $this->supplier_is_financial_capable,
            'supplier_is_authorized_dealer' => $this->supplier_is_authorized_dealer,
            'supplier_has_quality_material' => $this->supplier_has_quality_material,
            'supplier_can_comply_specs' => $this->supplier_can_comply_specs,
            'supplier_has_legal_issues' => $this->supplier_has_legal_issues,
            'fk_mgrfr_id' => $this->fk_mgrfr_id,
            'fk_conducted_by' => $this->fk_conducted_by,
            'fk_noted_by' => $this->fk_noted_by,

        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])

            ->andFilterWhere(['like', 'supplier_is_registered', $this->supplier_is_registered])
            ->andFilterWhere(['like', 'supplier_nursery', $this->supplier_nursery])
            ->andFilterWhere(['like', 'office.office_name', $this->fk_office_id])
            ->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
