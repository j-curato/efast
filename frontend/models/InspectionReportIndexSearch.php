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
                'division',
                'po_number',
                'inspector',
                'chairperson',
                'property_unit',
                'payee'
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
            $query->where('division =:division', ['division' => Yii::$app->user->identity->division]);
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

        $query->andFilterWhere(['like', 'ir_number', $this->ir_number]);
        // print_r($query->createCommand()->queryAll());
        // die();

        return $dataProvider;
    }
}
