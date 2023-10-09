<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\CheckRange;
use yii\data\ActiveDataProvider;

/**
 * CheckRangeSearch represents the model behind the search form of `app\models\CheckRange`.
 */
class CheckRangeSearch extends CheckRange
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'from', 'to'], 'integer'],
            [['province'], 'string'],
            [['bank_account_id'], 'safe'],
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
        $query = CheckRange::find();
        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = User::getUserDetails();
            $query->where('check_range.province = :province', ['province' => $user_data->employee->office->office_name]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith('bankAccount');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'check_range.id' => $this->id,

        ]);
        $query->andFilterWhere(['like', 'check_range.province', $this->province])
            ->andFilterWhere(['like', 'check_range.from', $this->from])
            ->andFilterWhere(['like', 'check_range.to', $this->to])
            ->andFilterWhere(['or', ['like', 'bank_account.account_number', $this->bank_account_id], ['like', 'bank_account.account_name', $this->bank_account_id]]);

        return $dataProvider;
    }
}
