<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;
use app\models\AdvancesLiquidation;

/**
 * AdvancesLiquidationSearch represents the model behind the search form of `app\models\AdvancesLiquidation`.
 */
class AdvancesLiquidationSearch extends AdvancesLiquidation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [[ 'payee_id', 'responsibility_center_id'], 'integer'],
            [[
                'check_date', 'check_number', 'dv_number', 'particular',
                'reporting_period',
                'fund_source',
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
        $query = AdvancesLiquidation::find();

        // add conditions that should always apply here
        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = User::getUserDetails();
            $query->where('province = :province', ['province' => $user_data->employee->office->office_name]);
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
        // $query->andFilterWhere([
        //     'id' => $this->id,
        //     'payee_id' => $this->payee_id,
        //     'responsibility_center_id' => $this->responsibility_center_id,
        // ]);

        $query->andFilterWhere(['like', 'check_date', $this->check_date])
            ->andFilterWhere(['like', 'check_number', $this->check_number])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'fund_source', $this->fund_source])
            ->andFilterWhere(['like', 'particular', $this->particular]);

        return $dataProvider;
    }
}
