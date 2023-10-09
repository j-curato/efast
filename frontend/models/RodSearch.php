<?php

namespace app\models;

use Yii;
use app\models\Rod;
use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;

/**
 * RodSearch represents the model behind the search form of `app\models\Rod`.
 */
class RodSearch extends Rod
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rod_number', 'province'], 'safe'],
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

        $query = Rod::find();

        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = User::getUserDetails();
            $query->where('province = :province', ['province' =>  $user_data->employee->office->office_name]);
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
        $query->andFilterWhere(['like', 'rod_number', $this->rod_number])
            ->andFilterWhere(['like', 'province', $this->province]);

        return $dataProvider;
    }
}
