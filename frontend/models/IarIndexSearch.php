<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\IarIndex;
use Yii;

/**
 * IarIndexSearch represents the model behind the search form of `app\models\IarIndex`.
 */
class IarIndexSearch extends IarIndex
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
                'iar_number',
                'ir_number',
                'rfi_number',
                'division',
                'unit',
                'unit_head',
                'inspector',
                'chairperson',
                'property_unit',
                'po_number',
                'payee',
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
        $query = IarIndex::find();



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

        $query->andFilterWhere(['like', 'iar_number', $this->iar_number])
            ->andFilterWhere(['like', 'rfi_number', $this->rfi_number])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'unit_head', $this->unit_head])
            ->andFilterWhere(['like', 'inspector', $this->inspector])
            ->andFilterWhere(['like', 'chairperson', $this->chairperson])
            ->andFilterWhere(['like', 'property_unit', $this->property_unit])
            ->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'payee', $this->payee]);


        return $dataProvider;
    }
}
