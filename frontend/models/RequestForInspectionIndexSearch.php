<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RequestForInspectionIndex;
use Yii;

/**
 * RequestForInspectionIndexSearch represents the model behind the search form of `app\models\RequestForInspectionIndex`.
 */
class RequestForInspectionIndexSearch extends RequestForInspectionIndex
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
                'division',
                'unit',
                'unit_head',
                'inspector',
                'chairperson',
                'property_unit',
                'po_number',
                'payee',
                'purpose',
                'project_name',
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
        $query = RequestForInspectionIndex::find();
        if (!yii::$app->user->can('super-user')) {
            $query->andWhere('division = :division', ['division' => Yii::$app->user->identity->division]);
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






        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'rfi_number', $this->rfi_number])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'unit_head', $this->unit_head])
            ->andFilterWhere(['like', 'inspector', $this->inspector])
            ->andFilterWhere(['like', 'chairperson', $this->chairperson])
            ->andFilterWhere(['like', 'property_unit', $this->property_unit])
            ->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'project_name', $this->project_name]);


        // print_r($query->createCommand()->getRawSql());
        // die();

        return $dataProvider;
    }
}
