<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\ParIndex;
use yii\data\ActiveDataProvider;

/**
 * ParIndexSearch represents the model behind the search form of `app\models\ParIndex`.
 */
class ParIndexSearch extends ParIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'par_number',
                    'par_date',
                    'rcv_by',
                    'act_usr',
                    'isd_by',
                    'location',
                    'property_number',
                    'acquisition_date',
                    'description',
                    'serial_number',
                    'unit_of_measure',
                    'article',
                    'is_unserviceable',
                    'office_name',
                ],
                'safe'
            ],
            [['acquisition_amount'], 'integer'],
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
        $query = ParIndex::find();

        // add conditions that should always apply here

        if (!Yii::$app->user->can('ro_property_admin')) {
            $user_data = User::getUserDetails();
            $query->andWhere('office_name = :office_name', ['office_name' => $user_data->employee->office->office_name]);
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
            'acquisition_amount' => $this->acquisition_amount,
        ]);

        $query->andFilterWhere(['like', 'par_number', $this->par_number])
            ->andFilterWhere(['like', 'par_date', $this->par_date])
            ->andFilterWhere(['like', 'rcv_by', $this->rcv_by])
            ->andFilterWhere(['like', 'act_usr', $this->act_usr])
            ->andFilterWhere(['like', 'isd_by', $this->isd_by])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'property_number', $this->property_number])
            ->andFilterWhere(['like', 'acquisition_date', $this->acquisition_date])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'unit_of_measure', $this->unit_of_measure])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'is_unserviceable', $this->is_unserviceable]);


        return $dataProvider;
    }
}
