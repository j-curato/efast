<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ChartOfAccounts;

/**
 * ChartOfAccountsSearch represents the model behind the search form of `app\models\ChartOfAccounts`.
 */
class ChartOfAccountsSearch extends ChartOfAccounts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','major_account_id', 'sub_major_account' ], 'integer'],
            [['uacs', 'general_ledger', 'account_group', 'current_noncurrent', 'enable_disable'], 'safe'],
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
        $query = ChartOfAccounts::find();

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
            'major_account_id' => $this->major_account_id,
            'sub_major_account' => $this->sub_major_account,
        ]);


        
        

        $query->andFilterWhere(['like', 'uacs', $this->uacs])
            ->andFilterWhere(['like', 'general_ledger', $this->general_ledger])
            ->andFilterWhere(['like', 'account_group', $this->account_group])
            ->andFilterWhere(['like', 'current_noncurrent', $this->current_noncurrent])
            ->andFilterWhere(['like', 'enable_disable', $this->enable_disable]);
            // ->andFilterWhere(['like', 'sub_major_account.name', $this->sub_major_account])
            // ->andFilterWhere(['like', 'major_accounts.name', $this->major_account_id]);

        return $dataProvider;
    }
}
