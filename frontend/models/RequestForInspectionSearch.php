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
    public function rules()
    {
        return [
            [['id', 'fk_chairperson', 'fk_inspector', 'fk_property_unit'], 'integer'],
            [['rfi_number', 'date', 'created_at'], 'safe'],
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
        $query->joinWith('division');
        if (!yii::$app->user->can('super-user')) {
            $query->andWhere('pr_office.division = :division', ['division' => Yii::$app->user->identity->division]);
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
            'date' => $this->date,
            'fk_chairperson' => $this->fk_chairperson,
            'fk_inspector' => $this->fk_inspector,
            'fk_property_unit' => $this->fk_property_unit,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'rfi_number', $this->rfi_number]);

        return $dataProvider;
    }
}
