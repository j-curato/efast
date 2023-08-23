<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RequestForInspection;
use Yii;

/**
 * RequestForInspectionSearch represents the model behind the search form of `app\models\RequestForInspection`.
 */
class RequestForInspectionSearch extends RequestForInspection
{
    /**
     * {@inheritdoc}
     */
    public $unit_head;
    public function rules()
    {
        return [
            [['id',], 'integer'],
            [[
                'rfi_number',
                'date',
                'created_at',
                'fk_chairperson',
                'fk_inspector',
                'fk_property_unit',
                'fk_pr_office_id',
                'unit_head',
                'fk_responsibility_center_id'
            ], 'safe'],
            // [[
            //     'rfi_number',
            //     'date',
            //     'created_at',
            //     'fk_chairperson',
            //     'fk_inspector',
            //     'fk_property_unit',
            //     'fk_pr_office_id',
            //     'unit_head',


            // ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process']
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
        $query = RequestForInspection::find();
        $query->joinWith('responsibilityCenter');
        if (!yii::$app->user->can('ro_inspection_admin')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('request_for_inspection.fk_office_id = :office', ['office' => $user_data->office->id]);
            if (!Yii::$app->user->can('ro_inspection_admin') || !Yii::$app->user->can('po_inspection_admin')) {

                $query->andWhere('request_for_inspection.fk_division_id = :division', ['division' => $user_data->divisionName->id ?? '']);
            }
        }
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




        $query->joinWith('chairperson as chairperson');
        $query->joinWith('inspector as inspector');
        $query->joinWith('propertyUnit as property_unit');
        // $query->joinWith('office.unitHead as unit_head');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'responsibility_center.name', $this->fk_responsibility_center_id])
            ->andFilterWhere(['like', 'rfi_number', $this->rfi_number])
            ->andFilterWhere([
                'or', ['like', 'chairperson.f_name', $this->fk_chairperson],
                ['like', 'chairperson.m_name', $this->fk_chairperson],
                ['like', 'chairperson.l_name', $this->fk_chairperson]
            ])
            ->andFilterWhere([
                'or', ['like', 'inspector.f_name', $this->fk_inspector],
                ['like', 'inspector.m_name', $this->fk_inspector],
                ['like', 'inspector.l_name', $this->fk_inspector]
            ])
            ->andFilterWhere([
                'or', ['like', 'property_unit.f_name', $this->fk_property_unit],
                ['like', 'property_unit.m_name', $this->fk_property_unit],
                ['like', 'property_unit.l_name', $this->fk_property_unit]
            ])
            ->andFilterWhere([
                'or', ['like', 'unit_head.f_name', $this->unit_head],
                ['like', 'unit_head.m_name', $this->unit_head],
                ['like', 'unit_head.l_name', $this->unit_head]
            ])
            ->andFilterWhere([
                'or', ['like', 'pr_office.division', $this->fk_pr_office_id],
                ['like', 'pr_office.unit', $this->fk_pr_office_id],
            ]);
        $query->orderBy('request_for_inspection.created_at DESC');

        // print_r($query->createCommand()->getRawSql());
        // die();

        return $dataProvider;
    }
}
