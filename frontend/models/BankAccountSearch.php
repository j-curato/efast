<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BankAccount;
use Yii;

/**
 * BankAccountSearch represents the model behind the search form of `app\models\BankAccount`.
 */
class BankAccountSearch extends BankAccount
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['account_number', 'account_name', 'province', 'created_at'], 'safe'],
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
        $user_data = Yii::$app->memem->getUserData();
        $province = strtolower($user_data->office->office_name);
        $query = BankAccount::find();
        // add conditions that should always apply here
        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $query->where(['province' => $province]);
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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'account_number', $this->account_number])
            ->andFilterWhere(['like', 'account_name', $this->account_name])
            ->andFilterWhere(['like', 'province', $this->province]);

        return $dataProvider;
    }
}
