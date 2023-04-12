<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rpcppe;
use Yii;

/**
 * RpcppeSearch represents the model behind the search form of `app\models\Rpcppe`.
 */
class RpcppeSearch extends Rpcppe
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'fk_book_id',
                'reporting_period',
                'certified_by',
                'approved_by',
                'verified_by',
                'verified_pos',
                'fk_chart_of_account_id',
                'fk_office_id',
                'fk_actbl_ofr',
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
        $query = Rpcppe::find();
        $query->joinWith('office');
        $query->joinWith('book');
        $query->joinWith('chartOfAccount');
        $query->joinWith('accountableOfficer');

        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('office.office_name =:office_name', ['office_name' => $user_data->office->office_name]);
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
        $query->andFilterWhere([]);

        $query
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'books.name', $this->fk_book_id])
            ->andFilterWhere(['like', 'office.office_name', $this->fk_office_id])
            ->andFilterWhere(['like', 'chart_of_accounts.general_ledger', $this->fk_chart_of_account_id])
            ->andFilterWhere([
                'or', ['like', 'employee.f_name', $this->fk_actbl_ofr],
                ['like', 'employee.l_name', $this->fk_actbl_ofr]
            ])
            ->andFilterWhere(['like', 'verified_by', $this->verified_by])
            ->andFilterWhere(['like', 'verified_pos', $this->verified_pos]);

        return $dataProvider;
    }
}
