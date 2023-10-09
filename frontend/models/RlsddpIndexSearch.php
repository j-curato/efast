<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\RlsddpIndex;
use yii\data\ActiveDataProvider;

/**
 * RlsddpIndexSearch represents the model behind the search form of `app\models\RlsddpIndex`.
 */
class RlsddpIndexSearch extends RlsddpIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['accountable_officer', 'supervisor', 'circumstances'], 'string'],
            [['id'], 'integer'],
            [['date', 'blotter_date'], 'safe'],
            [['serial_number', 'police_station', 'office_name', 'status'], 'string', 'max' => 255],
            [['blottered'], 'string', 'max' => 3],
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
        $query = RlsddpIndex::find();
        if (!Yii::$app->user->can('ro_property_admin')) {
            $user_data = User::getUserDetails();
            $query->andWhere('office_name = :office_name', ['office_name' => $user_data->employee->office->office_name]);
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

        $query->andFilterWhere(['like', 'accountable_officer', $this->accountable_officer])
            ->andFilterWhere(['like', 'supervisor', $this->supervisor])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'blotter_date', $this->blotter_date])
            ->andFilterWhere(['like', 'circumstances', $this->circumstances])
            ->andFilterWhere(['like', 'police_station', $this->police_station])
            ->andFilterWhere(['like', 'blottered', $this->blottered])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number]);

        return $dataProvider;
    }
}
