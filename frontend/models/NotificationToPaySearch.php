<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NotificationToPay;
use common\models\User;

/**
 * NotificationToPaySearch represents the model behind the search form of `app\models\NotificationToPay`.
 */
class NotificationToPaySearch extends NotificationToPay
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_due_diligence_report_id'], 'integer'],
            [[
                'serial_number', 'date', 'created_at', 'fk_office_id'
            ], 'safe'],
            [['matching_grant_amount', 'equity_amount', 'other_amount'], 'number'],
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
        $query = NotificationToPay::find();

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
            'date' => $this->date,
            'fk_due_diligence_report_id' => $this->fk_due_diligence_report_id,
            'matching_grant_amount' => $this->matching_grant_amount,
            'equity_amount' => $this->equity_amount,
            'other_amount' => $this->other_amount,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number]);
        $query->andFilterWhere(['like', 'office.office_name', $this->fk_office_id]);

        return $dataProvider;
    }
}
