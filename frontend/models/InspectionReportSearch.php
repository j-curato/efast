<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InspectionReport;

/**
 * InspectionReportSearch represents the model behind the search form of `app\models\InspectionReport`.
 */
class InspectionReportSearch extends InspectionReport
{
    public $rfi_number;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['ir_number',  'created_at', 'rfi_number'], 'safe'],
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
        $query = InspectionReport::find();





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
        // $query->joinWith('inspectionReportItems')
        //     ->joinWith('inspectionReportItems.requestForInspectionItem')
        //     ->joinWith('inspectionReportItems.requestForInspectionItem.requestForInspection')
        //     ->joinWith('inspectionReportItems.requestForInspectionItem.requestForInspection.office')
        //     ->joinWith('inspectionReportItems.requestForInspectionItem.requestForInspection.office.unitHead as unit_head')
        //     ->groupBy([
        //         'inspection_report.id',
        //         'inspection_report.ir_number',
        //         'request_for_inspection.rfi_number',
        //         'pr_office.division',
        //         'pr_office.unit',
        //         "CONCAT(unit_head.f_name,' ',LEFT(unit_head.m_name,1),'. ',unit_head.l_name,' ',IFNULL(unit_head.suffix,''))"
        //     ]);


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'ir_number', $this->ir_number]);
        // print_r($query->createCommand()->queryAll());
        // die();

        return $dataProvider;
    }
}
