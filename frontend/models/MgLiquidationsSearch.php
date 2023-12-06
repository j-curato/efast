<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MgLiquidations;
use common\models\User;
use Yii;

/**
 * MgLiquidationsSearch represents the model behind the search form of `app\models\MgLiquidations`.
 */
class MgLiquidationsSearch extends MgLiquidations
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_mgrfr_id'], 'integer'],
            [[
                'serial_number', 'reporting_period', 'created_at',
                'fk_office_id'
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
        $query = MgLiquidations::find();

        // add conditions that should always apply here
        $query->joinWith('office');
        if (!Yii::$app->user->can('ro_rapid_fma')) {
            $user_data = User::getUserDetails();
            $query->andWhere([
                'fk_office_id' => $user_data->employee->office->id
            ]);
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
            'fk_mgrfr_id' => $this->fk_mgrfr_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'office.office_name', $this->fk_office_id])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period]);

        return $dataProvider;
    }
}
