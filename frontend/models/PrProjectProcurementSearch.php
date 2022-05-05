<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrProjectProcurement;
use Yii;

/**
 * PrProjectProcurementSearch represents the model behind the search form of `app\models\PrProjectProcurement`.
 */
class PrProjectProcurementSearch extends PrProjectProcurement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'employee_id', 'pr_office_id'], 'safe'],
            [['amount'], 'number'],
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
        $query = PrProjectProcurement::find();

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
        $query->joinWith('office');
        $query->joinWith('employee');
        $query->joinWith('office');
        $province = strtolower(Yii::$app->user->identity->province);
        $division = strtolower(Yii::$app->user->identity->division);
        if (

            $province === 'ro' &&
            $division === 'sdd' ||
            $division === 'cpd' ||
            $division === 'idd' ||
            $division === 'ord'


        ) {
            $query->andWhere('pr_office.division = :division', ['division' => $division]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'pr_office.office', $this->pr_office_id])
            ->orFilterWhere(['like', 'pr_office.division', $this->pr_office_id])
            ->orFilterWhere(['like', 'pr_office.unit', $this->pr_office_id])
            ->orFilterWhere(['like', 'employee.f_name', $this->employee_id])
            ->orFilterWhere(['like', 'employee.l_name', $this->employee_id])
            ->orFilterWhere(['like', 'employee.l_name', $this->employee_id]);

        return $dataProvider;
    }
}
