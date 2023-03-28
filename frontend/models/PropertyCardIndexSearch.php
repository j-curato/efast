<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PropertyCardIndex;
use Yii;

/**
 * PropertyCardIndexSearch represents the model behind the search form of `app\models\PropertyCardIndex`.
 */
class PropertyCardIndexSearch extends PropertyCardIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['acquisition_amount'], 'number'],
            [[
                'pc_number',
                'par_number',
                'par_date',
                'location',
                'property_number',
                'serial_number',
                'unit_of_measure',
                'office_name',
                'is_unserviceable',
                'rcv_by',
                'act_usr',
                'isd_by',
                'description',
                'article',
                'acquisition_date'
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
        $query = PropertyCardIndex::find();

        // add conditions that should always apply here
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('office_name = :office_name', ['office_name' => $user_data->office->office_name]);
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
        ]);

        $query->andFilterWhere(['like', 'pc_number', $this->pc_number])
            ->andFilterWhere(['like', 'par_number', $this->par_number])
            ->andFilterWhere(['like', 'par_date', $this->par_date])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'property_number', $this->property_number])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'unit_of_measure', $this->unit_of_measure])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'is_unserviceable', $this->is_unserviceable])
            ->andFilterWhere(['like', 'rcv_by', $this->rcv_by])
            ->andFilterWhere(['like', 'act_usr', $this->act_usr])
            ->andFilterWhere(['like', 'isd_by', $this->isd_by])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'acquisition_date', $this->acquisition_date]);

        return $dataProvider;
    }
}
