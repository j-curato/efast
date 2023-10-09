<?php

namespace app\models;

use Yii;
use app\models\Fur;
use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;

/**
 * FurSearch represents the model behind the search form of `app\models\Fur`.
 */
class FurSearch extends Fur
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reporting_period', 'province', 'created_at'], 'safe'],
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

        $query = Fur::find();
        $query->joinWith('bankAccount');
        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = User::getUserDetails();
            $query->where('bank_account.province = :province', ['province' =>  $user_data->employee->office->office_name]);
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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'fur.reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'bank_account.province', $this->province]);

        return $dataProvider;
    }
}
