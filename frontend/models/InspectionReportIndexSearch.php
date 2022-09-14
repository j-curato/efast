<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InspectionReportIndex;
use Yii;

/**
 * InspectionReportIndexSearch represents the model behind the search form of `app\models\InspectionReportIndex`.
 */
class InspectionReportIndexSearch extends InspectionReportIndex
{
    public $rfi_number;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'ir_number',
                'rfi_number',
                'po_number',
                'inspector_name',
                'end_user',
                'requested_by_name',
                'payee_name',
                'responsible_center',
                'purpose',
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
        $query = InspectionReportIndex::find();

        if (!Yii::$app->user->can('super-user')) {
            $query->where('responsible_center =:division', ['division' => Yii::$app->user->identity->division]);
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

        $query->andFilterWhere(['like', 'ir_number', $this->ir_number])
            ->andFilterWhere(['like', 'rfi_number', $this->rfi_number])
            ->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'inspector_name', $this->inspector_name])
            ->andFilterWhere(['like', 'end_user', $this->end_user])
            ->andFilterWhere(['like', 'requested_by_name', $this->requested_by_name])
            ->andFilterWhere(['like', 'payee_name', $this->payee_name])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'responsible_center', $this->responsible_center]);


        $query->orderBy('ir_number');
        // print_r($query->createCommand()->queryAll());
        // die();

        return $dataProvider;
    }
}
